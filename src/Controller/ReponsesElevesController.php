<?php

namespace App\Controller;

use App\Entity\ReponsesEleves;
use App\Form\ReponsesElevesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReponsesElevesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reponses/eleves')]
class ReponsesElevesController extends AbstractController
{
    #[Route('/', name: 'reponses_eleves_index', methods: ['GET'])]
    public function index(ReponsesElevesRepository $reponsesElevesRepository): Response
    {
        return $this->render('reponses_eleves/index.html.twig', [
            'reponses_eleves' => $reponsesElevesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'reponses_eleves_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponsesElefe = new ReponsesEleves();
        $form = $this->createForm(ReponsesElevesType::class, $reponsesElefe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponsesElefe);
            $entityManager->flush();

            return $this->redirectToRoute('reponses_eleves_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponses_eleves/new.html.twig', [
            'reponses_elefe' => $reponsesElefe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reponses_eleves_show', methods: ['GET'])]
    public function show(ReponsesEleves $reponsesElefe): Response
    {
        return $this->render('reponses_eleves/show.html.twig', [
            'reponses_elefe' => $reponsesElefe,
        ]);
    }

    #[Route('/{id}/edit', name: 'reponses_eleves_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReponsesEleves $reponsesElefe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponsesElevesType::class, $reponsesElefe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reponses_eleves_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponses_eleves/edit.html.twig', [
            'reponses_elefe' => $reponsesElefe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reponses_eleves_delete', methods: ['POST'])]
    public function delete(Request $request, ReponsesEleves $reponsesElefe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reponsesElefe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponsesElefe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reponses_eleves_index', [], Response::HTTP_SEE_OTHER);
    }
}
