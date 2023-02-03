<?php

namespace App\Controller;

use App\Entity\Eleves;
use App\Entity\Examens;
use App\Entity\Questions;
use App\Form\ExamensType;
use App\Repository\ElevesRepository;
use App\Repository\ExamensRepository;
use App\Repository\QuestionnairesRepository;
use App\Repository\QuestionsRepository;
use App\Repository\ReponsesElevesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ExamensController extends AbstractController
{
    #[Route('admin/examens', name: 'admin_examens')]
    public function adminList(ExamensRepository $examensRepository): Response
    {
        return $this->render('admin/examens/adminList.html.twig', [
            'examens' => $examensRepository->findAll(),
        ]);
    }


    #[Route('admin/examens/create', name: 'examen_create')]
    public function create(Request $request, ExamensRepository $examensRepository,): Response
    {
        $examen = new Examens();
        $form = $this->createForm(ExamensType::class, $examen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $examen->setCreatedAt(new \DateTimeImmutable());

            $examensRepository->add($examen, true);

            $this->addFlash('success', 'L\'examen a bie été créé');
            return $this->redirectToRoute('examen_update');
        }

        return $this->render('admin/examens/form.html.twig', [
            'examenForm' => $form->createView(),
            'action' => 'create',
        ]);
    }

    #[Route('admin/examens/add_eleve/{id_eleve}', name: 'examens_add_eleve')]
    public function add_eleve(
        int $id_eleve,
        ExamensRepository $examensRepository,
        ElevesRepository $elevesRepository,
        Examens $examens

    ) {

        $eleve = $elevesRepository->find($id_eleve);
        $examens->addEleve($eleve);

        $examensRepository->add($examens, true);
    }
    #[Route('admin/examens/add_questionnaire/{id_eleve}', name: 'examens_add_questionnaire')]
    public function add_questionnaire(
        int $id_questionnaire,
        ExamensRepository $examensRepository,
        ElevesRepository $elevesRepository,
        Examens $examens

    ) {

        $questionnaire = $elevesRepository->find($id_questionnaire);
        $examens->addQuestionnaire($questionnaire);

        $examensRepository->add($examens, true);
    }

    #[Route('admin/examen/update/{id}', name: 'examen_update')]
    public function update(Request $request, Examens $examen, ExamensRepository $examensRepository, ElevesRepository $elevesRepository, QuestionnairesRepository $questionnairesRepository): Response
    {
        $form = $this->createForm(ExamensType::class, $examen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $examensRepository->add($examen, true);

            return $this->redirectToRoute('admin_examens');
        }

        return $this->render('admin/examens/form.html.twig', [
            'examenForm' => $form->createView(),
            'action' => 'modifier',
            'eleves' => $elevesRepository->findAll(),
            'questionnaires' => $questionnairesRepository->findAll()
        ]);
    }

    #[Route('admin/examens/{id}', name: 'examen_delete')]
    public function delete(Request $request, Examens $examen, ExamensRepository $examensRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $examen->getId(), $request->request->get('_token'))) {
            $examensRepository->remove($examen, true);
        }

        return $this->redirectToRoute('admin_examens');
    }

    #[Route('/admin/examens/show/{id}', name: 'examen_show', methods: ['GET'])]
    public function show(
        Examens $examen,
        ReponsesElevesRepository $reponsesElevesRepository,
        QuestionnairesRepository $questionnairesRepository
    ): Response {
        $questionnaire = $questionnairesRepository->find($examen->getQuestionnaire());
        $questions = $questionnaire->getQuestionnaireQuestion();

        $sujet = [];

        foreach ($questions as $question) {
            $reponses = [];
            $reponseEleve = $reponsesElevesRepository->findOneBy([
                'examens' => $examen->getId(),
                'questions' => $question->getId()
            ]);
            foreach ($question->getQuestionReponse() as $reponse) {
                if ($reponse->isSuccess()) {
                    if ($reponse->getId() === $reponseEleve->getReponses()) {
                        $statut = "success";
                    } else {
                        $statut = "warning";
                    }
                } else {
                    if ($reponse->getId() === $reponseEleve->getReponses()) {
                        $statut = "danger";
                    } else {
                        $statut = "light";
                    }
                }
                $reponses[] = [
                    "libelle"   => $reponse->getLibelle(),
                    "statut"    => $statut,
                ];
            }
            $sujet[] = [
                'texte'     => $question->getText(),
                'reponses'  => $reponses,
            ];
        }

        return $this->render('admin/examens/show.html.twig', [
            'sujet' => $sujet,
        ]);
    }
}
