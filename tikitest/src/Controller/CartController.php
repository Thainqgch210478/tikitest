<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\DBAL\Connection;
class CartController extends AbstractController
{
    #[Route('/cart/{id}', name: 'app_cart')]
    public function index($id, UserRepository $userRepository, CartRepository $cartRepository, ProductRepository $productRepository, ManagerRegistry $manager): Response
    {
        $user = $this->getUser();
        $product = $productRepository->findAll();
        $cart = $cartRepository->findAll();
        return $this->render('cart/index.html.twig', [
            'carts' => $cart,
            'user' => $user,
            'products' => $product

        ]);
    }


    #[Route('/cart/add/{id}', name: 'add_cart_product')]
    public function addProduct(ManagerRegistry $managerRegistry, Request $request,$id,ProductRepository $productR )
    {   $cart = new Cart;
        $user = $this->getUser();
        $product = $productR->find($id); 

      
        $cart->setCusid($user);
        $cart->setProductid($product);
        $cart->setQuantity(1);
        
        $manager = $managerRegistry->getManager();
        $manager->persist($cart);
        $manager->flush();
        return $this->renderForm('user/detailProduct.html.twig', [
            'carts' => $cart,
            'user' => $user,
            'product' => $product
        ]);
       
    }    

    #[Route('/cart/delete/{id}',name:'delete_cart_product')]

   

    public function deleteCart($id,ManagerRegistry $Mregistry, ProductRepository $repository,CartRepository $cartRepository){

        $cart = $cartRepository->find($id);
        $user = $this->getUser();
        $product = $repository->findAll();
        
        $manager = $Mregistry->getManager();

        $manager -> remove($cart);
        $manager->flush();

        return $this->renderForm('user/detailProduct.html.twig', [
            'carts' => $cart,
            'user' => $user,
            'product' => $product
        ]);

    }

    #[Route('/pay/{id}', name:'add_order')]

    public function addOrder($id,ManagerRegistry $managerRegistry,UserRepository $userR , Request $request, Connection $connection, CartRepository $cartRepository){

        $order = new Order;
               
        $user = $this->getUser();
        $carts = $cartRepository->findAll();
        $form = $this->createForm(OrderType::class, $order);
        $form->add('Submit', SubmitType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted()&&$form->isValid()){
            $order->setCusid($user);
             // $userid = $request->get('custId');
            $order->setCusid($user);
            $order->setStatus("Pending");
            $order->setPaymentmethod("Pending");
            $order->setTransportationmethod("Pending");
            

            $manager = $managerRegistry->getManager();
            
            $manager->persist($order);
            $manager->flush();

            //ADD TO ORDER DETAIL AND DELETE ALL CART
            $getLastestId = $connection->fetchAllAssociative("SELECT Max(id) FROM `order`");
            $getAllUserCart = $cartRepository->getAllUserCart($order->getCusid());
            //ADD TO ORDER DETAIL
            for($i = 0; $i < count($getAllUserCart); $i++){
                $orderDetai = new OrderDetails;
                $pid = $getAllUserCart[$i]->getProductid();
                $quantity = $getAllUserCart[$i]->getQuantity();
                $orderDetai->setOrderid($order);
                $orderDetai->setProductid($pid);
                $orderDetai->setQuantity($quantity);

                $managerCart = $managerRegistry->getManager();
                $managerCart->persist($orderDetai);
                $managerCart->flush();
            }
            //DELETE ALL USER CART
            for($i = 0; $i < count($getAllUserCart); $i++){
                $managerDeleteCart = $managerRegistry->getManager();
                $managerDeleteCart->remove($getAllUserCart[$i]);
                $managerDeleteCart->flush();
            }

            $this->addFlash('success', 'Add Product Successfully');
            return $this->redirectToRoute('app_user_product');
        }

        return $this->renderForm('order/orderForm.html.twig', [
            'formOrder'=>$form,
             'user'=>$user
        ]);
    }
}
