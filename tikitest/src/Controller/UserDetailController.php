<?php

namespace App\Controller;

use App\Entity\UserDetail;
use App\Repository\UserDetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserDetailController extends AbstractController
{
    #[Route('/user/detail/{id}', name: 'app_user_detail')]
    public function userDetail($id, UserDetailRepository $userDetail): Response
    {
        $user = $userDetail->find($id);
        if ($user == null) {
            $this->addFlash('Warning', 'Invalid user id !');
            return $this->redirectToRoute('app_login');
        }


        return $this->render('user_detail/index.html.twig', [
            'controller_name' => 'UserDetailController',
        ]);
    }
}
