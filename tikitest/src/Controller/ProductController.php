<?php

namespace App\Controller;

use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product')]
    public function allProduct(ProductRepository $repository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit_product')]
    public function editProduct(ProductRepository $repository, $id, ManagerRegistry $registry, Request $request): Response
    {
        $product = $repository->find($id);
        if($product==null){
            return $this->redirectToRoute('app_product');
        }else{
            $form = $this->createForm(ProductType::class, $product);
            $form->add('Submit', SubmitType::class);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $manager = $registry->getManager();
    
                $manager->persist($product);
                $manager->flush();
    
                return $this->redirectToRoute('app_product');
            }
            
            return $this->renderForm('product/detail.html.twig', [
                'productForm' => $form,
            ]);
        }
    }

    #[Route('/delete/{id}', name:'app_delete_product')]
    public function deleteProduct($id, ManagerRegistry $registry, ProductRepository $repository){
        $product = $repository->find($id);

        if($product!=null){
            $manager = $registry->getManager();
            $manager->remove($product);
            $manager->flush();
        }

        return $this->redirectToRoute('app_product');
    }
}
