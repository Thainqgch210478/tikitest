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
        $user = $this->getUser();
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
            'order'=>$order, 'users'=>$users, 'orderDetail'=>$orderDetails, 'products'=>$products, 'user'=>$user
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
    #[Route('/search', name:'app_search_order')]
    public function searchOrder(OrderRepository $orderRepository, Request $request, UserDetailRepository $userDetailRepository) : Response{
        $user = $this->getUser();
        $search = $request->get('searchOrder');
        $orderByWaiting = $orderRepository->searchOrderByWaitingStatus($search);
        $orderByCompleted = $orderRepository->searchOrderByCompletedStatus($search);
        $orderByCanceled = $orderRepository->searchOrderByWCanceledStatus($search);
        $users = $userDetailRepository->findAll();

        if($orderByWaiting != null){
            return $this->render('order/index.html.twig', [
                'orders'=>$orderByWaiting, 'users'=>$users, 'user'=>$user
            ]); 
        }
        else if($orderByCompleted != null){
            return $this->render('order/index.html.twig', [
                'orders'=>$orderByCompleted, 'users'=>$users, 'user'=>$user
            ]); 
        }
        else if($orderByCanceled != null){
            return $this->render('order/index.html.twig', [
                'orders'=>$orderByCanceled, 'users'=>$users, 'user'=>$user
            ]); 
        }else if ($orderByCanceled == null && $orderByCanceled == null && $orderByCompleted == null){
            $this->addFlash('notice', 'Order Not Found');
            return $this->render('order/index.html.twig', [
                'orders'=>$orderByCanceled, 'users'=>$users, 'user'=>$user
            ]); 
        }
    }

    #[Route('/waiting', name:'app_waiting_order')]
    public function searchByWaitingOrderStatus(OrderRepository $orderRepository, Request $request, UserDetailRepository $userDetailRepository){
        $user = $this->getUser();
        $orderByWaiting = $orderRepository->searchOrderBygStatus('Waiting');
        $users = $userDetailRepository->findAll();
        if($orderByWaiting!=null){
            return $this->render('order/index.html.twig', [
                'orders'=>$orderByWaiting, 'users'=>$users, 'user'=>$user
            ]); 
        }else{
            $this->addFlash('notice', 'Order Not Found');
            return $this->redirectToRoute('app_order');
        }

    }

    #[Route('/completed', name:'app_completed_order')]
    public function searchByCompletedOrderStatus(OrderRepository $orderRepository, Request $request, UserDetailRepository $userDetailRepository){
        $user = $this->getUser();
        $orderByCompleted = $orderRepository->searchOrderBygStatus('Completed');
        $users = $userDetailRepository->findAll();
        if($orderByCompleted!=null){
            return $this->render('order/index.html.twig', [
                'orders'=>$orderByCompleted, 'users'=>$users, 'user'=>$user
            ]); 
        }else{
            $this->addFlash('notice', 'Order Not Found');
            return $this->redirectToRoute('app_order');
        }

    }

    #[Route('/canceled', name:'app_canceled_order')]
    public function searchByCanceledOrderStatus(OrderRepository $orderRepository, Request $request, UserDetailRepository $userDetailRepository){
        $user = $this->getUser();
        $orderByCanceled = $orderRepository->searchOrderBygStatus('Canceled');
        $users = $userDetailRepository->findAll();
        if($orderByCanceled!=null){
            return $this->render('order/index.html.twig', [
                'orders'=>$orderByCanceled, 'users'=>$users, 'user'=>$user
            ]); 
        }else{
            $this->addFlash('notice', 'Order Not Found');
            return $this->redirectToRoute('app_order');
        }
    }
}
