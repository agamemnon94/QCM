<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Entity\Questionnaires;
use App\Form\QuestionnairesType;
use App\Repository\ClassesRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuestionnairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionnairesController extends AbstractController
{
    #[Route('admin/questionnaires', name: 'admin_questionnaires')]
    public function adminList(QuestionnairesRepository $questionnairesRepository): Response
    {
        return $this->render('admin/questionnaires/adminList.html.twig', [
            'questionnaires' => $questionnairesRepository->findAll(),
        ]);
    }

    #[Route('admin/questionnaires/create', name: 'questionnaire_create')]
    public function create(Request $request, QuestionnairesRepository $questionnairesRepository, QuestionsRepository $questionsRepository): Response
    {
        $questionnaire = new Questionnaires();
        $form = $this->createForm(QuestionnairesType::class, $questionnaire)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $questionnairesRepository->add($questionnaire, true);

            $this->addFlash('success', 'Le questionnaire a bien été crée');
            return $this->redirectToRoute('questionnaire_update', ['id' => $questionnaire->getId()]);
        }

        return $this->render('admin/questionnaires/form.html.twig', [
            'questionnaireForm' => $form->createView(),
            'listeQuestions'    =>  $questionsRepository->findAll(),
            'action'            => 'create'
        ]);
    }
    #[Route('admin/questionnaires/update/{id}', name: 'questionnaire_update')]
    public function update(
        Request $request,
        Questionnaires $questionnaire,
        QuestionnairesRepository $questionnairesRepository,
        QuestionsRepository $questionsRepository,
        ClassesRepository $classesRepository
    ): Response {
        $form = $this->createForm(QuestionnairesType::class, $questionnaire)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionnairesRepository->add($questionnaire, true);

            $this->addFlash('success', 'Le questionnaire a bien été modifié');
            return $this->redirectToRoute('admin_questionnaires');
        }

        return $this->render('admin/questionnaires/form.html.twig', [
            'questionnaireForm' => $form->createView(),
            //filtrer les quesions proposées via question posées ↓
            'questions' => $questionsRepository->findAll(),
            'classes' => $classesRepository->findAll(),
            'questionnaire_id' => $questionnaire->getId(),
            'questions_posees' => $questionnairesRepository->findQuestionsByQuestionnaire($questionnaire->getId()),
            'questions_dispo' => $questionsRepository->findQuestionsNotSelected($questionnaire->getId()),
            'classe_exam' => $questionnairesRepository->findClasseByQuestionnaire($questionnaire->getId()),
            'action' => 'update'
        ]);
    }

    #[Route('admin/questionnaires/add_question/{id}/{id_question}', name: 'questionnaire_add_question')]
    public function add_question(
        int $id_question,
        Questionnaires $questionnaire,
        QuestionsRepository $questionsRepository,
        QuestionnairesRepository $questionnairesRepository,
    ) {
        $question = $questionsRepository->find($id_question);
        $questionnaire->addQuestionnaireQuestion($question);

        $questionnairesRepository->add($questionnaire, true);

        $this->addFlash('success', 'La question a bien été ajoutée');
        return $this->redirectToRoute('questionnaire_update', ['id' => $questionnaire->getId()]);
    }

    #[Route('admin/questionnaires/add_classe/{id}/{id_classe}', name: 'questionnaire_add_classe')]
    public function add_classe(
        int $id_classe,
        Questionnaires $questionnaire,
        ClassesRepository $classesRepository,
        QuestionnairesRepository $questionnairesRepository
    ) {
        $classe = $classesRepository->find($id_classe);
        $questionnaire->addQuestionnaireClasse($classe);

        $questionnairesRepository->add($questionnaire, true);
        $this->addFlash('success', 'La classe a bien été ajoutée');
        return $this->redirectToRoute('questionnaire_update', ['id' => $questionnaire->getId()]);
    }


    #[Route('admin/questionnaires/remove_question/{id}/{id_question}', name: 'questionnaires_remove_question')]
    public function remove_question(
        int $id_question,
        Questionnaires $questionnaire,
        QuestionsRepository $questionsRepository,
        QuestionnairesRepository $questionnairesRepository,
    ) {

        $question = $questionsRepository->find($id_question);
        $questionnaire->removeQuestionnaireQuestion($question);

        $questionnairesRepository->add($questionnaire, true);

        $this->addFlash('success', 'La question a bien été supprimée');
        return $this->redirectToRoute('questionnaire_update', ['id' => $questionnaire->getId()]);
    }

    #[Route('admin/questionnaires/remove_classe/{id}/{id_classe}', name: 'questionnaires_remove_classe')]
    public function remove_classe(
        int $id_classe,
        Questionnaires $questionnaire,
        ClassesRepository $classesRepository,
        QuestionnairesRepository $questionnairesRepository
    ) {
        $classe = $classesRepository->find($id_classe);
        $questionnaire->removeQuestionnaireClasse($classe);

        $questionnairesRepository->add($questionnaire, true);

        $this->addFlash('success', 'La classe a bien été supprimée');
        return $this->redirectToRoute('questionnaire_update', ['id' => $questionnaire->getId()]);
    }

    #[Route('admin/questionnaires/delete/{id}', name: 'questionnaire_delete')]
    public function delete(Questionnaires $questionnaire, QuestionnairesRepository $questionnairesRepository): Response
    {
        $questionnairesRepository->remove($questionnaire, true);

        $this->addFlash('success', 'Le questionnaire a bien été supprimé');
        return $this->redirectToRoute('admin_questionnaires');
    }
}
