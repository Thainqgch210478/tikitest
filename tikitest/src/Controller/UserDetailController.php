<?php

namespace App\Controller;

use App\Entity\UserDetail;
use App\Repository\UserDetailRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('user/detail')]
class UserDetailController extends AbstractController
{
// <<<<<<< HEAD
    
// =========
    #[Route('/{id}', name: 'app_user_detail2')]
    public function userDetail($id, UserDetailRepository $userDetail): Response
    {
        $user = $userDetail->find($id);
        $userid= $this->getUser($user);

        if($user!=null){
            return $this->render('userBase.html.twig', [
                'user'=>$user
            ]);
        }
        return $this->redirectToRoute('app_product');
    }
// >>>>>>> 61d9b407388ab37dd3652adf28817e9dcda0b43c

    // #[Route('/edit{id}')]
    // public function editUser(UserRepository $userRepository,$id,ManagerRegistry,Request $request):Respone
    // {
    //     $user = $userRepository->find($id);

    // }
}
