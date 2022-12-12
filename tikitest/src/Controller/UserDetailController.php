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
    #[Route('/', name: 'app_user_detail')]
    public function userDetail($id, UserDetailRepository $userDetail): Response
    {
        $user = $userDetail->find($id);
        if($user!=null){
            return $this->render('userBase.html.twig', [
                'user'=>$user
            ]);
        }
        return $this->redirectToRoute('app_product');
    }

    // #[Route('/edit')]
    // public function editUser(UserRepository $userRepository,$id,ManagerRegistry,Request $request):Respone
    // {
    //     $user = $userRepository->find($id);

    // }
}
