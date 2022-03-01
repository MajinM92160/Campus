<?php

namespace App\Service\Panier;

use App\Repository\EventsRepository;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    public $session;
    public $eventRepository;

    public function __construct(SessionInterface $session, EventsRepository $eventRepository)
    {
        $this->session = $session;
        $this->eventRepository = $eventRepository;

    }

    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);
        
        $panierDetail = [];

        foreach ($panier as $id => $quantite):

            $panierDetail [] =[
                'events'=>$this->eventRepository->find($id),
                'quantity'=>$quantite
            ];

        endforeach;
        return $panierDetail;


    }

    public function add(int $id)
        {
            $panier = $this->session->get('panier', []);

            if (empty($panier[$id])):
                $panier[$id] = 1;   
            else:
                $panier[$id]++;
            endif;

            $this->session->set('panier', $panier);
        }


    public function delete(int $id)
        {
            $panier = $this->session->get('panier', []);

            if (!empty($panier[$id])):
                unset($panier[$id]);
            endif;

            $this->session->set('panier', $panier);
        }

        public function remove(int $id)
        {
            $panier = $this->session->get('panier', []);
    
            if (!empty($panier[$id]) && $panier[$id] !== 1):
                $panier[$id]--;
            else:
                unset($panier[$id]);
            endif;
    
            $this->session->set('panier', $panier);
        }
        

    




}