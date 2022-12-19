<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\UserDetailRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('user')]
class UserController extends AbstractController
{
    #[Route('/view', name: 'app_user_product')]
    public function allProduct(ProductRepository $respository,UserDetailRepository $userRepository): Response
    {   
        
        $user = $this->getUser();
        return $this->render('product/viewUserProduct.html.twig', [
            'products' => $respository->findAll(),
            'user'=> $user
        ]);
    }

    #[Route('/detail/{id}', name: 'app_user_detail')]
    public function userDetail($id, UserRepository $userDetail): Response
    {   
        $userd = $userDetail->find($id);
               
        $user = $this->getUser();
        
                    
        if($id!=null){
            return $this->render('user/userInfor.html.twig', [
                'user'=>$user,
                
            ]);
        }
        return $this->redirectToRoute('app_product');
    }

    #[Route('/edit/{id}', name: 'app_edit_user')]
    public function editUser(ProductRepository $repository, $id, ManagerRegistry $registry, Request $request): Response
    {
        $user = $repository->find($id);
        


    }

    #[Route('/view/{id}', name:'app_view_product')]
    public function viewProduct($id, ProductRepository $productRepository){
        $product = $productRepository->find($id);
        $userid= $this->getUser();
                
        if($product!=null){
            return $this->render('user/detailProduct.html.twig', [
                'product'=>$product,
                'user'=>$userid
            ]);
        }
        return $this->redirectToRoute('app_product');
    }


    

}
