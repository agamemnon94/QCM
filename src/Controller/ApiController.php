<?php

namespace App\Controller;

use Error;
use Exception;
use App\Entity\Classes;
use App\Entity\Reponses;
use App\Entity\Categories;
use Doctrine\ORM\EntityManager;
use App\Repository\ClassesRepository;
use App\Repository\ReponsesRepository;
use App\Repository\QuestionsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuestionnairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApiController extends AbstractController
{

    // Classes

    #[Route('/classes', name: 'api_classe_add', methods: ['POST'])]
    public function classe_ad(Request $request, ClassesRepository $classesRepository): Response
    {
        $data  = $request->request->all()['classes'];
        $classe = new Classes();
        $classe->setName($data['name']);
        $classe->setActive(false);
        $classesRepository->add($classe, true);
        return $this->json([
            'code' => 'success',
            'msg' => 'La classe a été ajoutée',
            'classe' => $classe
        ], 201);
    }

    #[Route('/classes/{id}', name: 'api_classe_remove', methods: ['DELETE'])]
    public function classe_delete(Classes $classe, ClassesRepository $classesRepository)
    {
        $classesRepository->remove($classe, true);
        return $this->json(['action' => 'delete', 'msg' => 'La classe à bien été supprimée', 'code' => 'success']);
    }

    // Questions

    #[Route('/question/add', name: 'api_question_add')]
    public function add(
        Request $request,
        QuestionnairesRepository $questionnairesRepository,
        QuestionsRepository $questionsRepository,
        EntityManagerInterface $em
    ): Response {

        $data = json_decode($request->getContent());
        $questionnaire = $questionnairesRepository->find($data->id_questionnaire);
        $question = $questionsRepository->find($data->id_question);
        $questionnaire->addQuestionnaireQuestion($question);
        $em->flush();

        return $this->json(['msg' => 'Question ajoutée'], 201);
    }

    #[Route('/question/remove', name: 'api_question_remove')]
    public function remove(
        Request $request,
        QuestionnairesRepository $questionnairesRepository,
        QuestionsRepository $questionsRepository
    ): Response {

        $data = json_decode($request->getContent());
        $questionnaire = $questionnairesRepository->find($data->id_questionnaire);
        $question = $questionsRepository->find($data->id_question);
        $questionnaire->removeQuestionnaireQuestion($question);
        $questionsRepository->remove($question, true);

        return $this->json(['msg' => 'Question supprimée'], 200);
    }

    // Réponses

    #[Route('/reponses', name: 'api_reponses_add', methods: ['POST'])]
    public function reponse_add(Request $request, ReponsesRepository $reponsesRepository, QuestionsRepository $questionsRepository): Response
    {
        // ↓ reponse_type2 correspond au nom de la class du formulaire
        // Dans le formulaire on a un name = reponse_type2[...nom du champ]
        $data = $request->request->all()['reponses_type2'];
        $questionId = $request->request->all()['question_id'];
        $reponse = new Reponses();
        $reponse->setLibelle($data['libelle']);
        $reponse->setSuccess(false);
        $question = $questionsRepository->find($questionId);
        $question->addQuestionReponse($reponse);
        $reponsesRepository->add($reponse, true);

        return $this->json([
            'code' => 'success',
            'msg' => 'La réponse à bien été ajoutée',
            'reponse' => $reponse
        ], 201);
    }

    #[Route('/reponses/{id}', name: 'api_reponse_remove', methods: ['DELETE'])]
    public function reponse_remove(Reponses $reponse, ReponsesRepository $reponsesRepository)
    {
        $reponsesRepository->remove($reponse, true);
        return $this->json([
            'action' => 'delete',
            'code' => 'succes',
            'msg' => 'La réponse a bien été supprimée'
        ]);
    }

    // Catégories

    #[Route('/categories', name: 'api_categories_list', methods: ['GET'])]
    public function index()
    {
        return $this->json('list');
    }

    #[Route('/categories', name: 'api_categorie_new', methods: ['POST'])]
    public function new(Request $request, CategoriesRepository $categoriesRepository, ValidatorInterface $validator)
    {
        // $data = $request->request->all()['categories'];
        //↑ idem ↓
        $data = $request->request->all('categories');

        // Validation depuis les contraintes de l'entité sans utilisation du formulaire
        $category = new Categories();
        $category->setName($data['name']);
        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            $msg = '';
            foreach ($errors as $error) {
                $msg .= $error->getMessage() . '<br>';
            }
            return $this->json([
                'code' => 'danger',
                'msg' => $msg,
                'category' => null,
            ], 201);
        } else {
            // $category = new Categories();
            // $category->setName($data['name']);
            $categoriesRepository->add($category, true);
            return $this->json([
                'code' => 'success',
                'msg' => 'La catégorie a bien été ajoutée',
                'category' => $category
            ], 201);
        }
    }

    #[Route('/categories/{id}', name: 'api_categorie_show', methods: ['GET'])]
    public function show(Categories $category)
    {
        return $this->json(['action' => 'show', 'data' => $category]);
    }

    #[Route('/categories/{id}', name: 'api_categorie_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Categories $category)
    {
        return $this->json(['action' => 'edit', 'id' => $category->getId()]);
    }

    #[Route('/categories/{id}', name: 'api_categorie_delete', methods: ['DELETE'])]
    public function category_delete(Categories $category, CategoriesRepository $categoriesRepository)
    {
        $categoriesRepository->remove($category, true);
        return $this->json(['action' => 'delete',  'msg' => 'La catégorie a bien été supprimée', 'code' => 'success']);
    }

    // Questionnaire

    #[Route('/questionnaire/add_classe/{id}/{id_questionnaire}', name: 'api_questionnaire_add_classe')]
    public function questionnaireAddClasse(Classes $classe, int $id_questionnaire, Request $request, QuestionnairesRepository $questionnairesRepository): Response
    {

        $questionnaire = $questionnairesRepository->find($id_questionnaire);
        $questionnaire->addQuestionnaireClasse($classe);
        $questionnairesRepository->add($questionnaire, true);

        return $this->json([
            'code' => 'success',
            'msg' => 'La classe a été ajoutée',
        ], 200);
    }

    #[Route('/questionnaire/remove_classe/{id}', name: 'api_questionnaire_remove_classe')]
    public function questionnaireremoveClasse(Classes $classe): Response
    {
        return $this->json([
            'code' => 'success',
            'msg' => 'La classe a été retirée',
        ], 200);
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }




    // #[Route('/classe/add', name: 'api_classe_add')]
    // public function addClasse(
    //     Request $request,
    //     QuestionnairesRepository $questionnairesRepository,
    //     ClassesRepository $classesRepository,
    //     EntityManagerInterface $em
    // ): Response {

    //     $data = json_decode($request->getContent());
    //     $questionnaire = $questionnairesRepository->find($data->id_questionnaire);
    //     $classe = $classesRepository->find($data->id_classe);
    //     $questionnaire->addQuestionnaireClasse($classe);
    //     $em->flush();

    //     return $this->json(['msg' => 'Classe ajoutée'], 201);
    // }
    // #[Route('/classe/remove', name: 'api_classe_remove')]
    // public function removeClasse(
    //     Request $request,
    //     QuestionnairesRepository $questionnairesRepository,
    //     ClassesRepository $classesRepository,
    //     EntityManagerInterface $em
    // ): Response {

    //     $data = json_decode($request->getContent());
    //     $questionnaire = $questionnairesRepository->find($data->id_questionnaire);
    //     $classe = $classesRepository->find($data->id_classe);
    //     $questionnaire->removeQuestionnaireClasse($classe);
    //     $em->flush();

    //     return $this->json(['msg' => 'Classe suprimmée'], 200);
    // }

    // #[Route('/reponse/add', name: 'api_responses_add', methods: ['POST'])]
    // public function addReponse(
    //     Request $request,
    //     QuestionsRepository $questionsRepository,
    //     ReponsesRepository $reponsesRepository,
    //     EntityManagerInterface $em
    // ): Response {

    //     $data = $request->request->all()['reponses'];
    //     $reponse = new Reponses();
    //     $reponse->setLibelle($data['libelle']);
    //     $reponsesRepository->save($reponse, true);
    //     return $this->json(['action' => 'new', 'data' => $reponse, 'msg' => 'reponse ajoutée'], 201);
    // }

    // #[Route('/classes', name: 'api_classes_list', methods: ['GET'])]
    // public function index()
    // {
    //     return $this->json('list');
    //     // dd($this->json('list'));
    // }

    // #[Route('/classes', name: 'api_classes_new', methods: ['POST'])]
    // public function new(Request $request, ClassesRepository $classesRepository)
    // {
    //     $data = $request->request->all()['classe'];
    //     $classe = new Classes();
    //     // dd($classe);
    //     $classe->setName($data['name']);
    //     // dd($data['name']);
    //     $classesRepository->add($classe, true);
    //     return $this->json(['action' => 'new', 'data' => $classe]);
    // }

    // #[Route('/classes/{id}', name: 'api_classe_show', methods: ['GET'])]
    // public function show(Classes $classes)
    // {
    //     return $this->json(['action' => 'show', 'data' => $classes]);
    // }

    // #[Route('/classes/{id}', name: 'api_classe_edit', methods: ['PUT', 'PATCH'])]
    // public function edit(Classes $classes)
    // {
    //     return $this->json(['action' => 'edit', 'id' => $classes->getId()]);
    // }

    // #[Route('/classes/{id}', name: 'api_classe_delete', methods: ['DELETE'])]
    // public function delete(Classes $classes, ClassesRepository $classesRepository)
    // {
    //     $classesRepository->remove($classes, true);
    //     return $this->json(['action' => 'delete', 'msg' => 'La catégorie a été supprimée']);
    // }
}
