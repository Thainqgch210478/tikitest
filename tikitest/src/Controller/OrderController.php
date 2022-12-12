<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderDetailsRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserDetailRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

#[IsGranted("ROLE_ADMIN")]
#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order')]
    public function index(OrderRepository $orderRepository, ManagerRegistry $managerRegistry, UserDetailRepository $userDetailRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findAll();
        $users = $userDetailRepository->findAll();
        return $this->render('order/index.html.twig', [
            'orders'=>$orders, 'users'=>$users, 'user'=>$user
        ]);
    }
    #[Route('/test/{id}', name: 'testid')]
    public function testID(UserDetailRepository $userDetailRepository, $id): Response
    {
        $userDetail = $userDetailRepository->searchUseryId($id);
        return $this->render('user_detail/index.html.twig', [
                'userDetail'=>$userDetail
        ]);
    }
    #[Route('/edit/{id}', name: 'app_edit_order')]
    public function edit(OrderRepository $orderRepository, ManagerRegistry $managerRegistry, UserDetailRepository $userDetailRepository, $id, OrderDetailsRepository $details, ProductRepository $productRepository, Request $request): Response
    {
        $order = $orderRepository->find($id);
        $orderDetails = $details->findAll();
        $users = $userDetailRepository->findAll();
        $products = $productRepository->findAll();

        $status = $request->get('status');
        if($status != null){
            $order->setStatus($status);
            $manager = $managerRegistry->getManager();
            $manager->persist($order);
            $manager->flush();
        }
        return $this->render('order/orderDetailAdmin.html.twig', [
            'order'=>$order, 'users'=>$users, 'orderDetail'=>$orderDetails, 'products'=>$products
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete_order')]
    public function delete(OrderRepository $orderRepository, ManagerRegistry $managerRegistry, UserDetailRepository $userDetailRepository): Response
    {
        $orders = $orderRepository->findAll();
        $users = $userDetailRepository->findAll();
        return $this->render('order/index.html.twig', [
            'orders'=>$orders, 'users'=>$users
        ]);
    }
}
