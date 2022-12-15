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
    
<<<<<<< HEAD
// ==========
=======
// =======
<<<<<<< HEAD
    #[Route('/', name: 'app_user_detail')]
=======
>>>>>>> 1ee0a2245489f6966aba6a2e827e39799043e9d9
    #[Route('/{id}', name: 'app_user_detail2')]
>>>>>>> c67cc1d0e8e6ac4c7bf0b51e66f580300085c80c
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
