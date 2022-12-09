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
use App\Controller\FileException;
use App\Controller\throwException;

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
            
            //B2: set tên mới cho ảnh => đảm bảo tên ảnh là duy nhất trong thư mục
            $imgName1 = uniqid(); //uniqid : tạo ra string duy nhất
            //B3: lấy ra đuôi (extension) của ảnh
            //Yêu cầu cần thay đổi code của entity Book
            $imgExtension1 = $img1->guessExtension();
            //B4: hoàn thiện tên mới cho ảnh (giữ đuôi cũ và thay tên mới)
            
            $imageName1 = $imgName1. "." . $imgExtension1;
            //VD: greenwich.jpg 
            //B5: di chuyển ảnh về thư mục chỉ định trong project
            try {
                $img1->move(
                $this->getParameter('product_image'),
                // $imgName1 sai tên file nên sẽ lấy phần tên tưj tạo từ uniqid
                $imageName1
                //di chuyển file ảnh upload về thư mục cùng với tên mới
                //note: cầu hình parameter trong file services.yaml
                );
            } catch (FileException $e) {
                throwException($e);
            }
            //B6: set dữ liệu của image vào object book
            $product->setImage1($imageName1);

            //B1: lấy ra ảnh vừa upload
            $img2 = $form->get('image2')->getData();
            //B2: set tên mới cho ảnh => đảm bảo tên ảnh là duy nhất trong thư mục
            $imgName2 = uniqid(); //uniqid : tạo ra string duy nhất
            //B3: lấy ra đuôi (extension) của ảnh
            //Yêu cầu cần thay đổi code của entity Book
            $imgExtension2 = $img2->guessExtension();
            //B4: hoàn thiện tên mới cho ảnh (giữ đuôi cũ và thay tên mới)
            $imageName2 = $imgName2 . "." . $imgExtension2;
            //VD: greenwich.jpg 
            //B5: di chuyển ảnh về thư mục chỉ định trong project
            try {
                $img2->move(
                $this->getParameter('product_image'),
                $imgName2
                //di chuyển file ảnh upload về thư mục cùng với tên mới
                //note: cầu hình parameter trong file services.yaml
                );
            } catch (FileException $e) {
                throwException($e);
            }
            //B6: set dữ liệu của image vào object book
            $product->setImage2($imageName2);


            //B1: lấy ra ảnh vừa upload
            $img3 = $form->get('image3')->getData();
            //B2: set tên mới cho ảnh => đảm bảo tên ảnh là duy nhất trong thư mục
            $imgName3 = uniqid(); //uniqid : tạo ra string duy nhất
            //B3: lấy ra đuôi (extension) của ảnh
            //Yêu cầu cần thay đổi code của entity Book
            $imgExtension3 = $img3->guessExtension();
            //B4: hoàn thiện tên mới cho ảnh (giữ đuôi cũ và thay tên mới)
            $imageName3 = $imgName3 . "." . $imgExtension3;
            //VD: greenwich.jpg 
            //B5: di chuyển ảnh về thư mục chỉ định trong project
            try {
                $img3->move(
                $this->getParameter('product_image'),
                // $imgName1
                //di chuyển file ảnh upload về thư mục cùng với tên mới
                //note: cầu hình parameter trong file services.yaml
                );
            } catch (FileException $e) {
                throwException($e);
            }
            //B6: set dữ liệu của image vào object book
            $product->setImage3($imageName3);

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
