<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Users;
use App\Form\EventType;
use App\Repository\EventsRepository;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;


class EventController extends AbstractController
{
    /**
     * @Route("addEvent", name="addEvent")
     * @Route("/editEvent/{id}", name="editEvent")
     */
    public function addEvent(Request $request, EntityManagerInterface $manager, EventsRepository $repository, $id = null)
    {
        

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] == "student"){
            return $this->redirectToRoute('listEvent');
        }

        $ajout = false;

        // Récupération des événements crées par l'utilisateur connecté (office)
        $events = $repository->findBy(['user'=>$this->getUser()]);

        if( !$id ){
            $event = new Events();
            $ajout = true;
        }else{
            $event = $repository->find($id);
        }


        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            
            if( empty($event->getPrice()) && $event->getFree() == 0){

                $this->addFlash('danger', 'Merci d\'indiquer un prix si votre évènement n\'est pas gratuit');
                return $this->redirectToRoute('addEvent');

            }

            $utilisateur = $this->getUser();

            $event->setUser($utilisateur);
            $manager->persist($event);
            $manager->flush();

            if( !$id ){
                $this->addFlash('success', 'Évènement ajouté avec succès');
            }else{
                $this->addFlash('success', 'Évènement modifié avec succès');
            }

            return $this->redirectToRoute('addEvent');
        }

        return $this->render('event/addEvent.html.twig', [
            'form' => $form->createView(),
            'events' => $events,
            'ajout' => $ajout
        ]);

    }


    /**
     * @Route("/deleteEvent/{id}", name="deleteEvent")
     */
    public function deleteEvent(Events $events, EntityManagerInterface $manager)
    {

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] == "student"){
        return $this->redirectToRoute('listEvent');
        }

        $this->addFlash('success', 'L\'évènement ' . $events->getName() . ' a été supprimé avec succès');
        $manager->remove($events);
        $manager->flush();

        return $this->redirectToRoute('listEvent');

    }





    // =========================== STUDENT ========================== //

    /**
     * @Route("/listEvent", name="listEvent")
     */
    public function listEvent(EventsRepository $repository)
    {
        if(empty($this->getUser())){
            return $this->redirectToRoute('home');
        }

        $checkRole = $this->getUser()->getRoles();

        if( $checkRole['0'] == "admin"){
            return $this->redirectToRoute('backMenu');
        }

        if( $checkRole['0'] == "office"){
            $role = "office";
        }


        if( $checkRole['0'] !== "office"){
            
        
            $test1 = $this->getUser()->getOffice();
            // dd($test1);
            // renvoie un tableau proxies (relation) avec les infos de l'office relation avec notre user connecté

            $test2 = $test1->getId();
            // dd($test2); 
            // envoie l'id de l'office 14

            $events = $repository->findBy(['user'=>$test2]);
            // dd($events);
            return $this->render('event/listEvent.html.twig', [
                'events' => $events
            ]);

        }else{

            $test2 = $this->getUser();
            $events = $repository->findBy(['user'=>$test2]);
            // dd($events);
            return $this->render('event/listEvent.html.twig', [
                'events' => $events
            ]);
        }
    }


    /**
     * @Route("/detailEvent/{id}", name="detailEvent")
     */
    public function detailEvent(EventsRepository $repository, Request $request, EntityManagerInterface $manager, $id)
    {

        $events = $repository->find($id);

        return $this->render("event/detailEvent.html.twig", [
            'events' => $events
        ]);

    }

    /**
     * @Route("/tickets", name="userTickets")
     */
    public function UserTickets( OrdersRepository $repository)
    {
        $orders = $repository->findBy(['user' => $this->getUser()]);

        return $this->render('event/tickets.html.twig', [
            'orders' => $orders
        ]);
    }





}
