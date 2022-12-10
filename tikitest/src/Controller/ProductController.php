<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Controller\FileException;
use App\Controller\throwException;
#[IsGranted("ROLE_ADMIN")]
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
                $img1 = $form->get('image1')->getData();
                $fileName1 = md5(uniqid()).'.'.$img1->guessExtension(); 
                $img1->move($this->getParameter('product_image'), $fileName1); 
                $product->setImage1($fileName1);
    
    
                $img2 = $form->get('image2')->getData();
                $fileName2 = md5(uniqid()).'.'.$img2->guessExtension(); 
                $img2->move($this->getParameter('product_image'), $fileName2); 
                $product->setImage2($fileName2);
    
    
                $img3 = $form->get('image3')->getData();
                $fileName3 = md5(uniqid()).'.'.$img3->guessExtension(); 
                $img3->move($this->getParameter('product_image'), $fileName3); 
                $product->setImage3($fileName3);

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

    #[Route('/view/{id}', name:'app_view_product')]
    public function viewProduct($id, ProductRepository $productRepository){
        $product = $productRepository->find($id);
        if($product!=null){
            return $this->render('product/viewProduct.html.twig', [
                'product'=>$product
            ]);
        }
        return $this->redirectToRoute('app_product');
    }

    #[Route('/add', name:'app_add_product')]
    public function addProduct(ManagerRegistry $managerRegistry, Request $request){
        $product = new Product;

        $form = $this->createForm(ProductType::class, $product);
        $form->add('Submit', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            //B1: lấy ra ảnh vừa upload
            $img1 = $form->get('image1')->getData();
            $fileName1 = md5(uniqid()).'.'.$img1->guessExtension(); 
            $img1->move($this->getParameter('product_image'), $fileName1); 
            $product->setImage1($fileName1);


            $img2 = $form->get('image2')->getData();
            $fileName2 = md5(uniqid()).'.'.$img2->guessExtension(); 
            $img2->move($this->getParameter('product_image'), $fileName2); 
            $product->setImage2($fileName2);


            $img3 = $form->get('image3')->getData();
            $fileName3 = md5(uniqid()).'.'.$img3->guessExtension(); 
            $img3->move($this->getParameter('product_image'), $fileName3); 
            $product->setImage3($fileName3);

            $manager = $managerRegistry->getManager();

            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('app_product');
        }

        return $this->renderForm('product/addProduct.html.twig', [
            'formAddProduct'=>$form
        ]);
    }
}
