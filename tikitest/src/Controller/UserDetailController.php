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
    

    // #[Route('/edit{id}')]
    // public function editUser(UserRepository $userRepository,$id,ManagerRegistry,Request $request):Respone
    // {
    //     $user = $userRepository->find($id);

    // }
}
