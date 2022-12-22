<?php

namespace App\Controller;
use App\Repository\UserDetailRepository;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
#[IsGranted("ROLE_ADMIN")]
#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories , 'user'=>$user
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
    #[Route('/add', name: 'app_add_category')]
    public function add(Request $request, CategoryRepository $categoryRepository, ManagerRegistry $registry): Response
    {
        $categories = $categoryRepository->findAll();
        $category = new Category;
        $categoryName = $request->get('categoryName');
        $category->setName($categoryName);

        if($categoryName!=null){
            $manager = $registry->getManager();

            $manager->persist($category);
            $manager->flush();
        }
        return $this->redirectToRoute('app_category');
    }

    #[Route('/edit/{id}', name: 'app_edit_category')]
    public function id(Request $request, CategoryRepository $categoryRepository, ManagerRegistry $registry, $id): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        $category = $categoryRepository->find($id);
        $categoryName = $request->get('categoryName');
        if($categoryName!=null){
            $category->setName($categoryName);
            $manager = $registry->getManager();

            $manager->persist($category);
            $manager->flush();
            $this->addFlash('notice', 'Edit Category Succesfully !');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/editCategory.html.twig', [
            'categories' => $categories , 'category'=>$category, 'user'=>$user
        ]);
    }

    #[Route('/delete/{id}', name:'app_delete_category')]
    public function deleteProduct($id, ManagerRegistry $registry, CategoryRepository $repository){
        $category = $repository->find($id);

        if($category!=null){
            if(count($category->getProducts())==0){
                $manager = $registry->getManager();
                $manager->remove($category);
                $manager->flush();
            }
            else{
                $this->addFlash('error', 'Cannot Delete this category because there are more than one product has this category');
            }
        }
        return $this->redirectToRoute('app_category');
    }
}
