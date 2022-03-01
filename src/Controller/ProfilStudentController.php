<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProfilStudentController extends AbstractController
{
    
    /**
     * @Route("/profilStudent/studentInfos", name="studentInfos")
     */
    public function studentInfos(UsersRepository $usersRepository)
    {
        $checkRole = $this->getUser()->getRoles();
        if( $checkRole['0'] !== "student"){
        return $this->redirectToRoute('listEvent');
        }
    
        $student = $this->getUser();
        // dd($student);

        return $this->render("profilStudent/studentInfos.html.twig", [
            'student' => $student
        ]);

    }


    /**
     * @Route("/editUserStudent",  name="editstudent")
     */
    public function editUser(EntityManagerInterface $manager, HttpFoundationRequest $request, UserPasswordHasherInterface $hasher, UsersRepository $repo,Session $session)
    {

        

        $user = $this->getUser();

        $form = $this->createForm(RegistrationType::class, $user, ['editstudent'=>true]);


        $form->handleRequest($request);

            //dd($form->getErrors());
            if ($form->isSubmitted() && $form->isValid()) :
                

             $manager->persist($user);
                
             $manager->flush();
               
            return $this->redirectToRoute('listEvent');

            endif;


        //dd($form->getErrors());
        if ($form->isSubmitted() && $form->isValid()) :
            
            $manager->persist($user);
            
            $manager->flush();
            return $this->redirectToRoute('listEvent');

        endif;

        return $this->render('security/editStudent.html.twig', [
            'user' => $user,
            'form' => $form->createView(),

        ]);

    }
}
