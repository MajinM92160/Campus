<?php

namespace App\Controller;

use App\Entity\Participations;
use App\Form\ParticipationsType;
use App\Repository\EventsRepository;
use App\Repository\ParticipationsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipationsController extends AbstractController
{

    /**
     * @Route("detailEvent/{id}", name="detailEvent")
     */
    public function ManageParticipation(EntityManagerInterface $manager, Request $request, ParticipationsRepository $participationsRepo, EventsRepository $eventsRepo, UsersRepository $usersRepo, $id = null){

// ============= AJOUT ET/OU MODIFICATION D'UNE PARTICIPATION ==============

        // La participation est null par défaut
        $add = false;

        // Récupération des données de l'événement en URL
        $infoEvent = $eventsRepo->find($id);

        // Récupération de l'ensemble des participations de l'évènement en URL
        $participations = $participationsRepo->findBy(['event' => $infoEvent]);

        // Récupération du User en cours
        $user = $this->getUser();

        // Récupération de la participation du User en cours sur l'événement en URL
        $result = $participationsRepo->findOneBy(['user' => $user, 'event' => $infoEvent]);

        // SI l'id n'est pas dans le partRepo ALORS c'est une nouvelle participation
        // SINON afficher la participation de l'id existant
        if ($result):
            $participation = $result;
        else:
            $participation = new Participations();
            $add = true;
        endif;

        // Soumission du formulaire
        if( !empty($_POST)){

            $participation->setStatus($request->request->get('status'));
            
            if ( strlen( $request->request->get('comment') ) < 1 ) :
                $participation->setComment(null);
            else :
                $participation->setComment( $request->request->get('comment'));
            endif;


            $participation->setEvent($infoEvent);

            $participation->setUser($this->getUser());

            $manager->persist($participation);
            $manager->flush();

            return $this->redirectToRoute('detailEvent',[ 
                'id'=>$id,
            ]);
         }

// ============================ AFFICHAGE DES PARTICIPATIONS ============================



        // Affichage des participants à l'événement

        $participationsOk = $participationsRepo->findBy([
            'event' => $infoEvent,
            'status' => 'ok']);

        // Affichage des personnes intéressé.es

        $participationsMaybe = $participationsRepo->findBy([
            'event' => $infoEvent,
            'status' => 'maybe']);

        // Affichage des personnes qui ne participent pas

        $participationsNo = $participationsRepo->findBy([
            'event' => $infoEvent,
            'status' => 'no']);

// ============================ RENDU SUR LA VUE ============================

        return $this->render('event/detailEvent.html.twig', [
            'result' => $result,
            'add' => $add,
            'infoEvent' => $infoEvent,
            'ok' => $participationsOk,
            'maybe' => $participationsMaybe,
            'no' => $participationsNo
        ]);
    }

}
