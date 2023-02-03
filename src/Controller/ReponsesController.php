<?php

namespace App\Controller;

use App\Entity\Reponses;
use App\Form\ReponsesType;
use App\Repository\ReponsesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReponsesController extends AbstractController
{
    #[Route('admin/reponses', name: 'admin_reponses')]
    public function adminList(ReponsesRepository $reponsesRepository): Response
    {
        return $this->render('admin/reponses/adminList.html.twig', [
            'reponses' => $reponsesRepository->findAll(),
        ]);
    }

    #[Route('admin/reponses/create', name: 'reponse_create')]
    public function create(Request $request, ReponsesRepository $reponsesRepository): Response
    {
        $reponse = new Reponses();
        $form = $this->createForm(ReponsesType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // dd($form);
            $reponsesRepository->add($reponse, true);

            $this->addFlash('success', 'La réponse a bien été créee');
            return $this->redirectToRoute('admin_reponses');
        }

        return $this->render('admin/reponses/form.html.twig', [
            'reponsesForm' => $form->createView(),
            'action' => 'create'
        ]);
    }

    #[Route('admin/reponses/update/{id}', name: 'reponse_update')]
    public function update(Request $request, Reponses $reponse, ReponsesRepository $reponsesRepository): Response
    {
        $form = $this->createForm(ReponsesType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $reponsesRepository->add($reponse, true);

            return $this->redirectToRoute('admin_reponses');
        }

        return $this->render('admin/reponses/form.html.twig', [
            'reponsesForm' => $form->createView(),
            'action' => 'update'
        ]);
    }

    #[Route('admin/reponses/delete/{id}', name: 'reponse_delete')]
    public function delete(Reponses $reponse, ReponsesRepository $reponsesRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $reponse->getId(), $request->request->get('_token'))) {

        // }
        $reponsesRepository->remove($reponse, true);
        $this->addFlash('success', 'La réponse a bien été supprimée');
        return $this->redirectToRoute('admin_reponses');
    }

    // #[Route('/{id}', name: 'reponses_show', methods: ['GET'])]
    // public function show(Reponses $reponse): Response
    // {
    //     return $this->render('reponses/show.html.twig', [
    //         'reponse' => $reponse,
    //     ]);
    // }
}
