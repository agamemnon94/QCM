<?php

namespace App\Controller;

use App\Entity\Eleves;
use App\Form\ElevesType;
use App\Repository\ClassesRepository;
use App\Repository\ElevesRepository;
use App\Repository\ExamensRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ElevesController extends AbstractController
{
    #[Route('admin/eleves', name: 'admin_eleves')]
    public function adminList(ElevesRepository $elevesRepository): Response
    {
        return $this->render('admin/eleves/adminList.html.twig', [
            'eleves' =>  $elevesRepository->findAll(),
        ]);
    }

    #[Route('admin/eleves/create', name: 'eleve_create')]
    public function create(Request $request, ElevesRepository $elevesRepository): Response
    {
        $eleve = new Eleves();
        $form = $this->createForm(ElevesType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $elevesRepository->add($eleve, true);

            $this->addFlash('success', 'L\'élève à bien été créé');
            return $this->redirectToRoute('admin_eleves');
        }
        return $this->render('admin/eleves/form.html.twig', [
            'elevesForm' => $form->createView(),
            'action' => 'create'
        ]);
    }

    #[Route('admin/eleves/update/{id}', name: 'eleve_update')]
    public function update(Request $request, Eleves $eleve, ElevesRepository $elevesRepository, ClassesRepository $classesRepository): Response
    {
        $form = $this->createForm(ElevesType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $elevesRepository->add($eleve, true);
            $this->addFlash('success', 'L\'élève à bien été modifié');
            return $this->redirectToRoute('admin_eleves');
        }

        return $this->render('admin/eleves/form.html.twig', [
            'elevesForm' => $form->createView(),
            'eleve_id' => $eleve->getId(),
            'classes' => $classesRepository->findAll(),
            'action' => 'update'
        ]);
    }

    #[Route('admin/eleves/add_classe/{id}/{id_classe}', name: 'eleve_add_classe')]
    public function add_classe(
        int $id_classe,
        Eleves $eleve,
        ClassesRepository $classesRepository,
        ElevesRepository $elevesRepository
    ) {
        $classe = $classesRepository->find($id_classe);
        $eleve->addEleveClasse($classe);

        $elevesRepository->add($eleve, true);
        $this->addFlash('success', 'La classe a bien été ajoutée');
        return $this->redirectToRoute('eleve_update', ['id' => $eleve->getId()]);
    }


    #[Route('/admin/eleves/delete/{id}', name: 'eleve_delete')]
    public function delete(Eleves $eleve, ElevesRepository $elevesRepository, ExamensRepository $examensRepository): Response
    {
        // Problème on ne peut pas supprimer un élève qui est dans un examen.
        //Avant de le supprimer on vérifie que l'éleve n'a pas d'examen
        // Si il en a un ->, msg d'erreur
        //Si nil n'en pas on supprime l'élève
        if (count($eleve->getExamens()) > 0) {
            $this->addFlash('danger', 'L\'élève est associé à un au moins un examen');
        } else {

            $elevesRepository->remove($eleve, true);
            $this->addFlash('success', 'L\'élève a bien été supprimé');
        }

        return $this->redirectToRoute('admin_eleves');
    }
}
