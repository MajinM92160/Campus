<?php

namespace App\Controller;
use App\Repository\EventsRepository;
use App\Repository\OrdersRepository;
use App\Entity\Orders;
use App\Entity\Cart;
use App\Service\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
   /**
     * @Route("/panier", name="panier")
     */
    public function index(): Response
    {
        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function fullCart(PanierService $panierService, OrdersRepository $repository,  $param = null)
    {
        if(empty($this->getUser())){
            return $this->redirectToRoute('home');
        }

        $panier = $panierService->getFullCart();
        $count = 0;
        foreach ($panier as $item):
            $count += $item['quantity'] * $item['events']->getPrice();
        endforeach;
        $total = $count;

        

        $order = $repository->findAll();
        $affich = false;
        if ($param):
            $affich = true;
        endif;


        $fullCart = $panierService->getFullCart();

        return $this->render('panier/panier.html.twig', [
            'fullcart' => $fullCart,
            'affich' => $affich,
            'total'=>$total
        ]);



    }

    /**
     * @Route("/addCart/{id}/{route}", name="addCart")
     */
    public function addCart($id, PanierService $panierService, $route=null )
    {
        if(empty($this->getUser())){
            return $this->redirectToRoute('home');
        }

        $panierService->add($id);

        ($panierService->getFullCart());
            
        if ($route == 'home'):
            return $this->redirectToRoute('panier');
        else:
            return $this->redirectToRoute('listEvent');
        endif;

    }




   /** 
     * @Route("/finalOrder/{id}", name="finalOrder")    
     */
    public function order(EventsRepository $repository , PanierService $panierService, EntityManagerInterface $manager, $id = null)
    {
        if(empty($this->getUser())){
            return $this->redirectToRoute('home');
        }

        $panier = $panierService->getFullCart();
        $count = 0;
        foreach ($panier as $item):
            $count += $item['quantity'] * $item['events']->getPrice();
        endforeach;
        $total = $count;
        

            

            $orders = new Orders();
            $orders->setDate(new \DateTime())->setUser($this->getUser());
            $panier = $panierService->getFullCart();

            foreach ($panier as $item):

                $cart = new Cart();
                $cart->setOrders($orders)->setEvent($item['events'])->setQuantity($item['quantity']);
                $manager->persist($cart);
                    $panierService->delete($item['events']->getId());
            endforeach;
            $manager->persist($orders);
            $manager->flush();
            $this->addFlash('success', "Merci pour votre achat");
            return $this->redirectToRoute('userTickets');




    }  

    /**
     * @Route("/removeCart/{id}", name="removeCart")
     *
     */
    public function removeCart($id, PanierService $panierService)
    {
        if(empty($this->getUser())){
            return $this->redirectToRoute('home');
        }
        
        $panierService->remove($id);
        return $this->redirectToRoute('panier');


    }


    
}
