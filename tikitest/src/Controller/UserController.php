<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('user')]
class UserController extends AbstractController
{
    #[Route('/view', name: 'app_user_product')]
    public function allProduct(ProductRepository $respository,$id,UserRepository $userRepository): Response
    {

        return $this->render('product/viewUserProduct.html.twig', [
            'products' => $respository->findAll(),
            
        ]);
    }
}
