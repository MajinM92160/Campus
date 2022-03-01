<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/mention" ,name="mention")
     */
    public function mention(): Response
    {
        // if(empty($this->getUser())){

        //     $this->addFlash('danger', 'Vous êtes déjà connecté');
            // return $this->redirectToRoute('home');

        // }

        return $this->render('footer/mention.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/politic" ,name="politic")
     */
    public function politic(): Response
    {
        // if(empty($this->getUser())){

        //     $this->addFlash('danger', 'Vous êtes déjà connecté');
            // return $this->redirectToRoute('home');

        // }

        return $this->render('footer/politic.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/" ,name="home")
     */
    public function index(): Response
    {
        // if(empty($this->getUser())){

        //     $this->addFlash('danger', 'Vous êtes déjà connecté');
            // return $this->redirectToRoute('home');

        // }

        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/registerConnectStudent", name="registerConnectStudent")
     */
    public function registerConnectStudent()
    {
        if(!empty($this->getUser())){
            $this->addFlash('danger', 'Vous êtes déjà connecté');
            return $this->redirectToRoute('home');
        }

        return $this->render('home/registerConnectStudent.html.twig');
    }


    /**
     * @Route("/registerConnectOffice", name="registerConnectOffice")
     */
    public function registerConnectOffice()
    {
        if(!empty($this->getUser())){
            $this->addFlash('danger', 'Vous êtes déjà connecté');
            return $this->redirectToRoute('home');
        }
        
        return $this->render('home/registerConnectOffice.html.twig');
    }
}
