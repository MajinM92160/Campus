<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @Route("/registerStudent",  name="registerStudent")
     */
    public function register(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher, UsersRepository $repo)
    {     
        
        if(!empty($this->getUser())){
            return $this->redirectToRoute('listEvent');
        }

        $research = $request->request->get("research");
    
    
        
        $code = $repo->findOneBy(['code_office' => $research]);

        // dd($code);

        $bde = 0;

        if ($code || !empty($request->request->get('office_id'))) :
            $bde = 1;

            $user = new Users();

            $form = $this->createForm(RegistrationType::class, $user, ['student'=>true]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) :

                //dd($_POST['office_id']);
                $office = $repo->find($_POST['office_id']);
                $mdp = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($mdp);
                $user->setOffice($office);
                $user->setRoles(['student']);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Félicitation, tu es bien inscrit.e ! Tu peux maintenant te connecter ! ');
                return $this->redirectToRoute('login');

            endif;

            return $this->render('security/registerStudent.html.twig', [
                'form' => $form->createView(),
                'office_id' => $code->getId(),
                'office_name' => $code->getName(),
                'office_address' => $code->getAddress(),
                'office_phone' => $code->getPhone(),
                'office_pres' => $code->getPresentation()
            ]);

        else:
            $this->addFlash('danger', 'Code invalide');
            return $this->redirectToRoute('formCheck');

        endif;
        
      
    }

    /**
     * @Route("/registerOffice",  name="registerOffice")
     */
    public function registerOffice(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher, UsersRepository $repo){


        if(!empty($this->getUser())){
            return $this->redirectToRoute('listEvent');
        }

        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user, [
            'office' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) :

            $research = $form->getData()->getCodeOffice();
            $code = $repo->findOneBy(['code_office' => $research]);
    
            if ($code):
                $this->addFlash('danger', 'Ce code existe déjà');
                return $this->redirectToRoute('registerOffice');
            endif;

            $mdp = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($mdp);
            $user->setOffice(null);
            $user->setRoles(['office']);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Félicitation, tu es bien inscrit.e ! Tu peux maintenant te connecter ! ');
            return $this->redirectToRoute('login');

        endif;
        
        return $this->render('security/registerOffice.html.twig', [
            'form' => $form->createView(),

        ]);

    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {

        // $this->addFlash('success', 'Vous êtes à présent connecté');
        // if(!empty($this->getUser())){
        //     $this->addFlash('danger', 'Vous êtes déjà connecté');
        // }

        // if(!empty($_POST)):
        //     $this->addFlash('success', 'Tu es connecté.e !');
        // endif;

        if(!empty($this->getUser())){
            return $this->redirectToRoute('listEvent');
        }

        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

        if(empty($this->getUser())){
            return $this->redirectToRoute('listEvent');
        }


        $this->addFlash('success', 'Tu es déconnecté.e ');
    }



    /**
     * @Route("/formCheck", name="formCheck")
     */
    public function checkCode(Request $request, UsersRepository $usersRepository)
    {
        if(!empty($this->getUser())){
            return $this->redirectToRoute('listEvent');
        }

        return $this->render('security/check.html.twig');
    }

    /**
     * @Route("/emailForm", name="emailForm")
     * @Route("/emailSend", name="emailSend")
     */
    public function email(MailerInterface $mailer, Request $request)
    {

        if (!empty($_POST)):

            $mess = $request->request->get('message');
            $nom = $request->request->get('surname');
            $prenom = $request->request->get('name');
            $motif = $request->request->get('need');
            $from = $request->request->get('email');

            $email = (new TemplatedEmail())
                ->from($from)
                ->to('campus.app.contact@gmail.com')
                ->subject($motif)
                ->text('Sending emails is fun again!')
                ->htmlTemplate('contact/template_email.html.twig');
            $cid = $email->embedFromPath('img/logo1.png', 'logo');

            // pass variables (name => value) to the template
            $email->context([
                'message' => $mess,
                'nom' => $nom,
                'prenom' => $prenom,
                'subject' => $motif,
                'from' => $from,
                'cid' => $cid,
                'liens' => 'https://127.0.0.1:8000',
                'objectif' => 'Accéder au site'

            ]);

            $mailer->send($email);


            return $this->redirectToRoute("home");


        endif;

        return $this->render('contact/form_email.html.twig');

    }

    /**
     * @Route("/resetPassword", name="resetPassword")
     */
    public function resetPassword()
    {

        return $this->render('security/resetPassword.html.twig');
    }

    /**
     * @Route("/resetToken", name="resetToken")
     */
    public function resetToken(UsersRepository $repository, Request $request, EntityManagerInterface $manager, MailerInterface $mailer)
    {
        $user = $repository->findOneBy(['email' => $request->request->get('email')]);

        if ($user):

            $token = uniqid();
            $user->setToken($token);
            $manager->persist($user);
            $manager->flush();

            $email = (new TemplatedEmail())
                ->from('campus.app.contact@gmail.com')
                ->to($request->request->get('email'))
                ->subject('Demande de réinitialisation de mot de passe')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('contact/template_email.html.twig');
            $cid = $email->embedFromPath('uploads/logo.png', 'logo');

            // pass variables (name => value) to the template
            $email->context([
                'message' => 'Vous avez fait une demande de réinitialisation de mot de passe, veuillez cliquer sur le liens ci dessous',
                'nom' => "",
                'prenom' => "",
                'subject' => 'demande de réinitialisation',
                'from' => 'campus.app.contact@gmail.com',
                'cid' => $cid,
                'liens' => 'https://127.0.0.1:8000/resetForm?token=' . $token . '&i=' . $user->getId(),
                'objectif' => 'Réinitialiser'

            ]);

            $mailer->send($email);


            $this->addFlash('success', 'Un Email vient de vous être envoyer!');
            return $this->redirectToRoute('login');
        else:
            $this->addFlash('danger', 'Aucun compte existant à cette adresse mail');

            return $this->redirectToRoute('resetPassword');
        endif;


    }


    /**
     * @Route("/resetForm", name="resetForm")
     */
    public function resetForm(UsersRepository $repository)
    {

        if (isset($_GET['token'])):
            $user = $repository->findOneBy(['id' => $_GET['i'], 'token' => $_GET['token']]);
            if ($user):

                return $this->render('security/resetForm.html.twig', [
                    'id' => $user->getId()
                ]);

            else:

                $this->addFlash('danger', 'Une erreur s\'est produite, veuillez réiterer votre demande');
                return $this->redirectToRoute('resetPassword');
            endif;


        endif;



    }

   /**
     * @Route("/finalReset", name="finalReset")
     */
    public function finalReset(UsersRepository $repository, EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher)
    {
        $user = $repository->find($request->request->get('id'));
        if ($request->request->get('password') == $request->request->get('confirm_password')):


        $mdp=$hasher->hashPassword($user, $request->request->get('password'));
        $user->setPassword($mdp);
        $user->setToken(null);
        $manager->persist($user);
        $manager->flush();

            $this->addFlash('success', 'Mot de passe réinitialisé, connectez vous à présent');
            return $this->redirectToRoute('login');

        else:
            $this->addFlash('danger', 'Les mots de passe ne correspondent pas');
            return $this->render('security/resetForm.html.twig', [
                'id' => $user->getId()
            ]);
        endif;


    }

}
