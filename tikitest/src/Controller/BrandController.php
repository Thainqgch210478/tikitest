<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
#[IsGranted("ROLE_ADMIN")]
#[Route('/brand')]
class BrandController extends AbstractController
{
    #[Route('/', name: 'app_brand')]
    public function index(BrandRepository $brandRepository): Response
    {
        $brands = $brandRepository->findAll();
        return $this->render('brand/index.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/add', name: 'app_add_brand')]
    public function add(Request $request, BrandRepository $brandRepository, ManagerRegistry $registry): Response
    {
        $brand= new Brand;
        $brandName = $request->get('brandName');
        $brand->setName($brandName);
        $imgName = $request->get('imageBrand');
        $brand->setImage($imgName);

        if($brandName!=null && $imgName!=null){
            $manager = $registry->getManager();
            $manager->persist($brand);
            $manager->flush();
        }
        return $this->redirectToRoute('app_brand');
    }

    #[Route('/edit/{id}', name: 'app_edit_brand')]
    public function id(Request $request, BrandRepository $brandRepository, ManagerRegistry $registry, $id): Response
    {
        $brands = $brandRepository->findAll();
        $brand = $brandRepository->find($id);
        $brandName = $request->get('brandName');
        $imageName = $request->get('imageBrand');
        if($brandName!=null&&$imageName!=null){
            $brand->setName($brandName);
            $brand->setImage($imageName);
            $manager = $registry->getManager();

            $manager->persist($brand);
            $manager->flush();

            return $this->redirectToRoute('app_brand');
        }
        return $this->render('brand/editBrand.html.twig', [
            'brands' => $brands  , 'brand'=>$brand 
        ]);
    }

    #[Route('/delete/{id}', name:'app_delete_brand')]
    public function deleteProduct($id, ManagerRegistry $registry, BrandRepository $repository){
        $brand = $repository->find($id);

        if($brand!=null){
            if(count($brand->getProducts())==0){
                $manager = $registry->getManager();
                $manager->remove($brand);
                $manager->flush();
            }
        }
        return $this->redirectToRoute('app_brand');
    }
}
