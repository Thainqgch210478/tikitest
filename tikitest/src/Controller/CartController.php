<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

}
