<?php

namespace App\Controller;

use App\Entity\Eleves;
use App\Entity\Examens;
use App\Form\SessionExamType;
use App\Repository\ElevesRepository;
use App\Repository\ExamensRepository;
use App\Repository\QuestionnairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionExamController extends AbstractController
{
    #[Route('/session/exam', name: 'session_exam')]
    public function index(Request $request, ElevesRepository $elevesRepository, QuestionnairesRepository $questionnairesRepository, ExamensRepository $examensRepository): Response
    {
        // $sessionExam = new Examens;
        // dump($request);

        $form = $this->createForm(SessionExamType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $data = $request->request->all();
            extract($data['session_exam']);
            $eleve = new Eleves();
            $eleve->setFirstname($prenom);
            $eleve->setLastname($nom);
            $elevesRepository->add($eleve);

            $questionnaire = $questionnairesRepository->findOneBy(['form_code' => $formCode]);
            // dd($questionnaire);
            $examen = new Examens();
            $examen->setEleve($eleve);
            $examen->setQuestionnaire($questionnaire);
            $examensRepository->add($examen, true);

            // Récupérer la classe actie depuis questionnaire
            //Créer la relation -> classe_eleves

            return $this->redirectToRoute('session_examen', ['id' => $examen->getId()]);
        }

        return $this->render('session_exam/index.html.twig', [
            'sessionForm' => $form->createView(),
        ]);
    }

    #[Route('session/examen/{id}', name: 'session_examen')]
    public function examen(Examens $examen)
    {
        dd($examen);
    }
}
