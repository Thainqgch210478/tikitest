<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
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

    #[Rotue('/cart/delete/{id}',name:'delete_cart_product')]
    public function deleteCart($id,ManagerRegistry $Mregistry, ProductRepository $repository,CartRepository $cartRepository){
        $cart = $cartRepository->find($id);
        
        $manager = $Mregistry->getManager();

        $manager -> remove($cart);
        $manager->flush();

        return $this->redirectToRoute('app_cart');

    }

    #[Route('/pay', name:'add_order')]
    public function addOrder(ManagerRegistry $managerRegistry,UserRepository $userR , Request $request){
        $order = new Order;
       
        
        $user = $this->getUser();
        $form = $this->createForm(OrderType::class, $order);
        $form->add('Submit', SubmitType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted()&&$form->isValid()){
            $userid = $request->get('custId');
            $order->setCusid($userid);
            $manager = $managerRegistry->getManager();

            $manager->persist($order);
            $manager->flush();
            $this->addFlash('success', 'Add Product Successfully');
            return $this->redirectToRoute('app_cart');
        }

        return $this->renderForm('order/orderForm.html.twig', [
            'formOrder'=>$form,
             'user'=>$user
        ]);
    }
}
