<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Users;
use App\Form\EventType;
use App\Repository\EventsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilOfficeController extends AbstractController
{
    
// PAGE D'ACCUEIL :

    /**
     * @Route("/profil/office", name="office")
     */
    public function office()
    {

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] == "student"){
           return $this->redirectToRoute('addEvent');
        }

        $office = $this->getUser();

        return $this->render('profil/office.html.twig', [
            'office' => $office
        ]);

    }

// PAGE BOUTON EVENEMENTS :

    // =========== LISTE DES EVENEMENTS AVEC MODIF ============

    /**
     * @Route("/profil/officeListEvent", name="officeListEvent")
     * @Route("/profil/officeEditEvent/{id}", name="officeEditEvent")
     */
    public function officeListEvent(EventsRepository $repository, Request $request, EntityManagerInterface $manager, $id = null)
    {

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] == "student"){
           return $this->redirectToRoute('addEvent');
        }

        $ajout = false;

        // Récupération l'ID de l'utilisateur ACTUELLEMENT connecté
        $infOffice = $this->getUser()->getId();
        // Récupération des événements crées par l'utilisateur connecté (office)
        $events = $repository->findBy(['user'=>$infOffice]);

        if( !$id){
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
                return $this->redirectToRoute('officeEditEvent');

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

            return $this->redirectToRoute('detailEvent', 
                ['id' => $event->getId()]);
        }


        return $this->render('profil/officeListEvent.html.twig', [
            'events' => $events,
            'form' => $form->createView(),
            'ajout' => $ajout
        ]);

    }

    // ========== SUPPRESSION DES EVENEMENTS ===============

    // /**
    //  * @Route("/officeDeleteEvent/{id}", name="officeDeleteEvent")
    //  */
    // public function deleteEvent(Events $events, EntityManagerInterface $manager)
    // {

    //     $checkRole = $this->getUser()->getRoles();
    //     if( $checkRole['0'] == "student"){
    //        return $this->redirectToRoute('officeListEvent');
    //     }

    //     $this->addFlash('success', 'L\'évènement ' . $events->getName() . ' a été supprimé avec succès');
    //     $manager->remove($events);
    //     $manager->flush();
    //     return $this->redirectToRoute('officeListEvent');

    // }


// PAGE BOUTON MES INFOS :


    /**
     * @Route("/profil/officeInfos", name="officeInfos")
     */
    public function officeInfos(UsersRepository $repository)
    {

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] == "student"){
           return $this->redirectToRoute('listEvent');
        }

        $office = $this->getUser();

        return $this->render("profil/officeInfos.html.twig", [
            'office' => $office
        ]);
    }

    





    /**
     * @Route("/profil/listStudents", name="listStudents")
     */
    public function listStudents(UsersRepository $repository)
    {

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] == "student"){
           return $this->redirectToRoute('listEvent');
        }

        $student = $repository->findBy(['office'=>$this->getUser()]);
        // dd($student);

        return $this->render("profil/listStudents.html.twig", [
            'student' => $student
        ]);

    }

    /**
     * @Route("profil/deleteStudent/{id}", name="deleteStudent")
     */
    public function deleteStudent(UsersRepository $repository, EntityManagerInterface $manager, Users $users)
    {

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] == "student"){
           return $this->redirectToRoute('listEvent');
        }

        $this->addFlash('success', 'L\'étudiant ' . $users->getFirstName() . ' a été supprimé avec succès');
        $manager->remove($users);
        $manager->flush();
        return $this->redirectToRoute('listStudents');


    }












}
