<?php

namespace App\Controller;

use App\Entity\Eleves;
use App\Entity\Examens;
use App\Entity\Questionnaires;
use App\Entity\ReponsesEleves;
use App\Repository\ElevesRepository;
use App\Repository\ExamensRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuestionnairesRepository;
use App\Repository\ReponsesElevesRepository;
use App\Repository\ReponsesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiExamenController extends AbstractController
{
    //Formulaire de connexion
    #[Route('/api/examen/connexion', name: 'api_exam_connexion')]
    public function exam_connexion(
        Request $request,
        QuestionnairesRepository $questionnairesRepository,
        ElevesRepository $elevesRepository,
        ExamensRepository $examensRepository
    ): Response {
        $data = $request->request->all();
        $questionnaire = $questionnairesRepository->findOneBy(['form_code' => $data['code']]);
        $eleve = new Eleves();
        $eleve->setFirstname($data['firstname']);
        $eleve->setLastname('');
        $eleve->setEmail(time() . '@toto.com');
        $elevesRepository->add($eleve, true);
        $examen = new Examens();
        $examen->setEleve($eleve);
        $examen->setQuestionnaire($questionnaire);
        $examensRepository->add($examen, true);

        return $this->json([
            // 'eleve' => $eleve->getId(), 
            'questionnaire' => $questionnaire->getId(),
            'examen' => $examen->getId()
        ], 200);
    }

    // Affichage des consignes
    #[Route('/api/examen/consignes/{id}', name: 'api_exam_consignes')]
    public function exam_consignes(Questionnaires $questionnaire): Response
    {
        return $this->json(['consignes' => $questionnaire->getConsigne()], 200);
    }

    // Affichage de questions
    #[Route('/api/examen/question/{id}/{offset}', name: 'api_exam_question', methods: ['GET'])]
    public function exam_question(
        Request $request,
        QuestionsRepository $questionsRepository,
        Questionnaires $questionnaire,
        int $offset
    ): Response {
        // r??cup??rer le questionnaire
        // ??? pour r??cup??rer toutes les questions ce dont je n'ai pas besoin dans le cas pr??cis ???
        //    $questions =  $questionnairesRepository->findAll();
        $question = $questionsRepository->findNextQuestion($questionnaire->getId(), $offset - 1);
        // aller chercher la premi??re question  (DQL avec LIMIT 1 et OFFSET ) setMaxResults / SetFirstResult
        // retourne texte de la question + tableau des r??ponses (objets)
        if ($question) {
            $reponses = $question[0]->getQuestionReponse();
            $texteQuestion = $question[0]->getText();
            $questionId = $question[0]->getId();
        } else {
            $texteQuestion = $questionId = $reponses = null;
        }
        return $this->json([
            'question' => $texteQuestion,
            'reponses'  => $reponses,
            'questionId' => $questionId,
        ], 200);
    }

    // Traitement de la r??ponse
    #[Route('/api/examen/reponse', name: 'api_exam_reponse', methods: ['POST'])]
    public function exam_reponse(
        Request $request,
        ReponsesElevesRepository $reponsesElevesRepository,
        ExamensRepository $examensRepository,
        QuestionsRepository $questionsRepository,
        ReponsesRepository $reponsesRepository
    ): Response {
        // r??cup??rer les donn??es du formulaire
        $data = $request->request->all();
        // dd($data);
        // ??crire dans reponses_eleves
        $reponse = new ReponsesEleves();
        $reponse->setReponses((int) $data['reponse']);
        $reponse->setCommentaires('');
        // $success = $reponsesRepository->find($data['reponse']);
        $reponse->setSuccess($reponsesRepository->find($data['reponse'])->isSuccess());
        // relation Examens


        // Objet Examen
        $examen = $examensRepository->find($data['idExamen']);
        $reponse->setExamens($examen);

        // relation Question : idem examen
        $question = $questionsRepository->find($data['questionId']);
        $reponse->setQuestions($question);

        $reponsesElevesRepository->save($reponse, true);
        // retourner les donn??es de la question suivante (si plus de question : retourner null/false)
        return $this->json(['msg' => 'ok'], 200);
    }


    // Affichage de r??sultats
    #[Route('/api/examen/resultats', name: 'api_exam_resultats')]
    public function exam_resultats(): Response
    {


        // return $this->json([], 200);
        return $this->render('admin/examen/show.html.twig', []);
    }
}
