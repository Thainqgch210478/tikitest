<?php

namespace App\Controller;

use App\Entity\UserDetail;
use App\Form\UserType;
use App\Repository\OrderDetailsRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\UserDetailRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[IsGranted("ROLE_USER")]
#[Route('user')]
class UserController extends AbstractController
{
    #[Route('/view', name: 'app_user_product')]
    public function allProduct(ProductRepository $respository, UserDetailRepository $userRepository): Response
    {

        $user = $this->getUser();
        return $this->render('product/viewUserProduct.html.twig', [
            'products' => $respository->findAll(),
            'user' => $user
        ]);
    }

    #[Route('/detail/{id}', name: 'app_user_detail')]
    public function userDetail($id, UserRepository $userDetail,UserDetailRepository $userDetailR): Response
    {
        $userd = $userDetail->find($id);
        $user = $this->getUser();

        if ($userd != null) {
            return $this->render('user/userInfor.html.twig', [
                'user' => $user,
                'userDetail' => $userd
                
            ]);
        }
        return $this->redirectToRoute('app_product');
    }

    #[Route('/edit/{id}', name: 'app_edit_user')]
    public function editUser(ProductRepository $repository, $id, ManagerRegistry $registry, Request $request, UserDetailRepository $userDetailRepository): Response
    {
         
        $userd = $userDetailRepository->find($id);
        $user = $this->getUser();

        if ($userd == null) {
            $userT = new UserDetail();
            $userT->setUserid($user);

            $form = $this->createForm(UserType::class, $userT);
            $form->add('Submit', SubmitType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $registry->getManager();
                $manager->persist($userT);
                $manager->flush();
                return $this->render('user/userInfor.html.twig', [
                    'user' => $user,
                    'userDetail' => $userd
                    
                ]);
                
            }
            // $userd->setName($nameD);
            $this->addFlash('success', 'Add Product Successfully');
                return $this->renderForm('user_detail/userEdit.html.twig', [
                    'user' => $user,
                    // 'userid' => $userd,
                    'userForm'=> $form,
                    'userDetail' => $userd
                    

                ]);
             }
             
    }


   
    
    #[Route('/myorder', name: 'user_view_order')]
    public function viewmyorder(OrderRepository $orderRepository, UserDetailRepository $userDetailRepository, UserRepository $userRepository, Request $request)
    {
        $user = $this->getUser();
        $search = $request->get('uid');
        $orders = $orderRepository->searchOrderByCusid($search);
        $users = $userDetailRepository->findAll();
            return $this->render('user_detail/userOrderDetail.html.twig', [
                'orders'=>$orders, 'users'=>$users, 'user'=>$user
            ]); 
        
    }
    #[Route('/myorder/detail/{id}', name: 'user_view_order_detail')]
    public function edit(OrderRepository $orderRepository, ManagerRegistry $managerRegistry, UserDetailRepository $userDetailRepository, $id, OrderDetailsRepository $details, ProductRepository $productRepository, Request $request): Response
    {
        $user = $this->getUser();
        $order = $orderRepository->find($id);
        $orderDetails = $details->findAll();
        $users = $userDetailRepository->findAll();
        $products = $productRepository->findAll();
        return $this->render('user_detail/userOrderInfor.html.twig', [
            'order'=>$order, 'users'=>$users, 'orderDetail'=>$orderDetails, 'products'=>$products, 'user'=>$user
        ]);
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

    #[Route('/search', name:'app_user_search_product')]
    public function searchProduct(ProductRepository $productRepository, Request $request){
        $productName = $request->get('searchProduct');
        $product = $productRepository->searchProductByName($productName);
        $userid= $this->getUser();
                
        if($product!=null){
            return $this->render('product/viewUserSearchProduct.html.twig', [
                'products' => $product,
                'user'=> $userid
            ]);
        }
        return $this->redirectToRoute('app_user_product');
    }
    

}
