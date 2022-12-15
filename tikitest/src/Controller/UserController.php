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
        $user = $userDetail->find($id);
        $userid= $this->getUser($user);
       
        if($id!=null){
            return $this->render('user/userInfor.html.twig', [
                'user'=>$userid    
            ]);
        }
        return $this->redirectToRoute('app_product');
    }

    #[Route('/edit/{id}', name: 'app_edit_user')]
    public function editUser(ProductRepository $repository, $id, ManagerRegistry $registry, Request $request): Response
    {
        $user = $repository->find($id);
        if($user==null){
            return $this->redirectToRoute('app_product');
        }else{
            $form = $this->createForm(UserType::class, $user);
            $form->add('Submit', SubmitType::class);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $img1 = $form->get('image1')->getData();
                $fileName1 = md5(uniqid()).'.'.$img1->guessExtension(); 
                $img1->move($this->getParameter('product_image'), $fileName1); 
                $user->setImage1($fileName1);
    
    
                $img2 = $form->get('image2')->getData();
                $fileName2 = md5(uniqid()).'.'.$img2->guessExtension(); 
                $img2->move($this->getParameter('product_image'), $fileName2); 
                $user->setImage2($fileName2);
    
    
                $img3 = $form->get('image3')->getData();
                $fileName3 = md5(uniqid()).'.'.$img3->guessExtension(); 
                $img3->move($this->getParameter('product_image'), $fileName3); 
                $user->setImage3($fileName3);

                $manager = $registry->getManager();
    
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Edit Product Successfully');
                return $this->redirectToRoute('app_product');
            }
            
            return $this->renderForm('product/detail.html.twig', [
                'productForm' => $form,
            ]);
        }
    }

    #[Route('/view/{id}', name:'app_view_product')]
    public function viewProduct($id, ProductRepository $productRepository){
        $product = $productRepository->find($id);
        if($product!=null){
            return $this->render('user/detailProduct.html.twig', [
                'product'=>$product
            ]);
        }
        return $this->redirectToRoute('app_product');
    }


    

}
