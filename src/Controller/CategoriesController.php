<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController
{

    #[Route('admin/categories', name: 'admin_categories')]
    public function adminlist(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('admin/categories/adminList.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'form' => $this->createForm(CategoriesType::class)->createView(),

        ]);
    }

    #[Route('/admin/categories/create', name: 'category_create')]
    public function create(Request $request, CategoriesRepository $categoriesRepository): response
    {
        $category = new categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categoriesRepository->add($category, true);

            $this->addflash('succes', 'La catégorie a bien été créée');
            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('admin/categories/form.html.twig', [
            'categoriesForm' => $form->createView(),
            'action' => 'create'
        ]);
    }
    #[Route('/admin/categories/update/{id}', name: 'category_update')]
    public function update(Categories $category, Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->add($category, true);

            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute(('admin_categories'));
        }
        return $this->render('admin/categories/form.html.twig', [
            'categoriesForm' => $form->createView(),
            'action' => 'update'
        ]);
    }

    #[Route('/admin/categories/delete/{id}', name: 'category_delete')]
    public function delete(Categories $category, CategoriesRepository $categoriesRepository): Response
    {
        // $manager = $managerRegistry->getManager();
        // $manager->remove($category);
        // $manager->flush();

        $categoriesRepository->remove($category, true);

        $this->addFlash('success', 'Catégorie supprimée');
        return $this->redirectToRoute('admin_categories');
    }
}
