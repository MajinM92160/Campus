<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Users;
use App\Repository\EventsRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

// ====================================== MENU =================================

    /**
     * @Route("/admin/backMenu", name="backMenu")
     */
    public function backMenu()
    {
        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "admin"){
           return $this->redirectToRoute('listEvent');
        }


        return $this->render('admin/backMenu.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

// ====================================== GESTION DES BDE =================================

    /**
     * @Route("/admin/gestBde", name="gestBde")
     * @Route("/admin/gestBde/{id}", name="gestBdeStudent")
     */
    public function gestBde(EventsRepository $eventsRepository, UsersRepository $usersRepository, Request $request, EntityManagerInterface $manager, $id = null)
    {
        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "admin"){
           return $this->redirectToRoute('listEvent');
        }


        if( !$id){

           $offices = $usersRepository->findByOffice(null);
        //    dd($offices);

           return $this->render("admin/gestBde.html.twig", [
                'offices' => $offices
           ]);
        }else{
            $students = $usersRepository->findByOffice($id);
            
            $offices = $usersRepository->findById($id);
            // dd($offices);

            return $this->render("admin/gestBde.html.twig", [
                'students' => $students,
                'offices' => $offices
            ]);
        }
    }

    /**
     * @Route("/deleteBde/{id}", name="deleteBde")
     */
    public function deleteBde(Users $users, EntityManagerInterface $manager)
    {
        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "admin"){
           return $this->redirectToRoute('listEvent');
        }

        $manager->remove($users);
        $manager->flush();
        return $this->redirectToRoute('gestBde');
    }

    /**
     * @Route("/deleteStudentAdmin/{id}", name="deleteStudentAdmin")
     */
    public function deleteStudentAdmin(Users $users, EntityManagerInterface $manager)
    {
        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "admin"){
           return $this->redirectToRoute('listEvent');
        }

        $manager->remove($users);
        $manager->flush();
        return $this->redirectToRoute('gestBde');
    }

    /**
     * @Route("/admin/bdeEvents/{id}", name="bdeEvents")
     */
    public function bdeEvents(Users $users, Request $request, EntityManagerInterface $manager, EventsRepository $repository, UsersRepository $usersRepository, $id = null)
    {

        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "admin"){
           return $this->redirectToRoute('listEvent');
        }

        $events = $repository->findByUser($id);

        $office = $usersRepository->findById($id);

        return $this->render("admin/bdeEvents.html.twig", [
            'events' => $events,
            'office' => $office
        ]);
    }

    /**
     * @Route("/deleteEventAdmin/{id}", name="deleteEventAdmin")
     */
    public function deleteEventAdmin(Events $events, EntityManagerInterface $manager)
    {
        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "admin"){
           return $this->redirectToRoute('listEvent');
        }

        $manager->remove($events);
        $manager->flush();
        return $this->redirectToRoute('gestBde');
    }

// ====================================== GESTION DES ETUDIANTS =================================

    /**
     * @Route("/admin/gestStudent", name="gestStudent")
     * @Route("/admin/gestStudent/{id}", name="gestStudentBde")
     */
    public function gestStudent(UsersRepository $usersRepository, Request $request, EntityManagerInterface $manager, $id = null)
    {
        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "admin"){
           return $this->redirectToRoute('listEvent');
        }

        if( !$id){


            //foreach userrepository findbyOffice -> student

            // $students = $usersRepository->findBy();

            // essayer avec une boucle foreach $office en sé

            // $students = $usersRepository->findByOffice(null);
            // dd($students);

            $offices = $usersRepository->findByOffice(null);
            // dd($offices);


            foreach($offices as $students){
                $students = $usersRepository->findByOffice($offices);
                // dd($students);
            }
            // dd($students);

            return $this->render("admin/gestStudent.html.twig", [
                'students' => $students,
                'offices' => $offices
            ]);
        }else{

            $students = $usersRepository->findByOffice($id);
            $offices = $usersRepository->findById($id);

            return $this->render("admin/gestStudent.html.twig", [
                'students' => $students,
                'offices' => $offices
            ]);

        }

    }









}
