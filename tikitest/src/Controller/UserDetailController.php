<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserDetailController extends AbstractController
{
    #[Route('/user/detail', name: 'app_user_detail')]
    public function index(): Response
    {
        return $this->render('user_detail/index.html.twig', [
            'controller_name' => 'UserDetailController',
        ]);
    }
}
