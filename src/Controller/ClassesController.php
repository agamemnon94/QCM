<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Form\ClassesType;
use App\Repository\ClassesRepository;
use App\Repository\ElevesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassesController extends AbstractController
{

    #[Route('admin/classes', name: 'admin_classes', methods: ['GET'])]
    public function adminList2(ClassesRepository $classesRepository): Response
    {

        return $this->render('/admin/classes/adminList2.html.twig', [
            'classes' => $classesRepository->findAll(),
            'form' => $this->createForm(ClassesType::class)->createView(),
        ]);
    }

    // #[Route('admin/classes', name: 'admin_classes')]
    // public function adminList(ClassesRepository $classesRepository): Response
    // {
    //     return $this->render('/admin/classes/adminList.html.twig', [
    //         'classes' => $classesRepository->findAll(),
    //     ]);
    // }

    // #[Route('admin/classes/create', name: 'classe_create')]
    // public function create(Request $request, ClassesRepository $classesRepository): Response
    // {
    //     $classe = new Classes();
    //     $form = $this->createForm(ClassesType::class, $classe);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $classes = $classesRepository->findAll();
    //         $classeNames = [];
    //         foreach ($classes as $existingClasse) {
    //             $classeNames[] = strtolower($existingClasse->getName());
    //         }

    //         if (in_array(strtolower($form['name']->getData()), $classeNames)) {

    //             $this->addFlash('danger', 'Le nom de cette classe est déjà utilisé');
    //             return $this->redirectToRoute('admin_classes');
    //         }
    //         $classesRepository->add($classe, true);

    //         $this->addFlash('success', 'La classe à bien été créée');
    //         return $this->redirectToRoute('admin_classes');
    //     }
    //     return $this->render('admin/classes/form.html.twig', [
    //         'classesForm' => $form->createView(),
    //         'action' => 'create'
    //     ]);
    // }

    // #[Route('admin/classes/update/{id}', name: 'classe_update')]
    // public function update(Request $request, Classes $classe, ClassesRepository $classesRepository): Response
    // {
    //     $form = $this->createForm(ClassesType::class, $classe);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $classeNames = [];
    //         foreach ($classe as $existingClasse) {
    //             $classeNames[] = strtolower($existingClasse->getName());
    //         }

    //         if (in_array(strtolower($form['name']->getData()), $classeNames)) {

    //             $this->addFlash('danger', 'Le nom de cette classe est déjà utilisé');
    //             return $this->redirectToRoute('admin_classes');
    //         }

    //         $classesRepository->add($classe, true);

    //         $this->addFlash('success', 'La classe à bien été modifiée');
    //         return $this->redirectToRoute('admin_classes');
    //     }
    //     return $this->render('admin/classes/form.html.twig', [
    //         'classesForm' => $form->createView(),
    //         'action' => 'update'
    //     ]);
    // }

    // #[Route('admin/classes/delete/{id}', name: 'classe_delete')]
    // public function delete(CLasses $classe, ClassesRepository $classesRepository): Response
    // {

    //     $classesRepository->remove($classe, true);

    //     $this->addFlash('success', 'La classe a bien été supprimée');
    //     return $this->redirectToRoute('admin_classes');
    // }
}
