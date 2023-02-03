<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Entity\Reponses;
use App\Form\ReponsesType;
use App\Form\QuestionsType;
use App\Form\ReponsesType2;
use App\Repository\ReponsesRepository;
use App\Repository\QuestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuestionnairesRepository;
use App\Repository\ReponsesElevesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Question\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionsController extends AbstractController
{
    #[Route('/admin/questions', name: 'admin_questions')]
    public function adminList(QuestionsRepository $questionsRepository): Response
    {
        return $this->render('admin/questions/adminList.html.twig', [
            'questions' => $questionsRepository->findAll(),
        ]);
    }

    #[Route('admin/questions/create', name: 'question_create')]
    public function create(Request $request, QuestionsRepository $questionsRepository,): Response
    {
        $question = new Questions();
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);
        // dd($form->createView());
        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['img']->getData();
            if ($infoImg !== null) {
                $extensionImg = $infoImg->guessExtension();

                $imgName = time() . '-qi-1.' .  $extensionImg;

                $infoImg->move($this->getParameter('question_img_dir'), $imgName);

                $question->setImg($imgName);
            }
            $questionsRepository->add($question, true);

            $this->addFlash('success', 'La question a bien été créee');
            return $this->redirectToRoute('question_update', [
                'id' => $question->getId(),
            ]);
        }
        return $this->render('admin/questions/form.html.twig', [
            'questionsForm' => $form->createView(),
            'reponses_proposees' => [],
            'action' => 'create'
        ]);
    }

    #[Route('admin/questions/update/{id}', name: 'question_update')]
    public function edit(Request $request, Questions $question, QuestionsRepository $questionsRepository, ReponsesRepository $reponsesRepository): Response
    {
        // l'id dans l'url correspond au paramètre que je donne dans la vue
        // ↓ ce n'est pas un paramètre qui vinet du fomulaire ↓
        // dd($request);
        $form = $this->createForm(QuestionsType::class, $question);
        $formResp = $this->createForm(ReponsesType2::class, null, ['action' => $this->generateUrl('question_add_resp'), 'method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['img']->getData();
            $oldImgName = $question->getImg();
            $oldImgPath = $this->getParameter('question_img_dir') . '/' . $oldImgName;
            // ↓ champ "no_img" du QuestionsType ↓
            if ($form['no_img']->getData()) {
                // Supprimer l'image
                if ($oldImgName !== null) {
                    if (file_exists($oldImgPath)) {
                        unlink($oldImgPath);
                    }
                    $question->setImg('');
                }
            } else {
                if (!empty($infoImg)) {

                    if (!empty($oldImgName)) {
                        if (file_exists($oldImgPath)) {
                            unlink($oldImgPath);
                        }
                    }
                    $extensionImg = $infoImg->guessExtension();
                    $imgName = time() . '-qi-1.' .  $extensionImg;
                    $infoImg->move($this->getParameter('question_img_dir'), $imgName);
                    $question->setImg($imgName);
                }
            };

            $questionsRepository->add($question, true);

            $this->addFlash('success', 'La question a bien été modifiée');
            return $this->redirectToRoute('admin_questions');
        }

        return $this->render('admin/questions/form.html.twig', [
            'questionsForm' => $form->createView(),
            'responseForm' => $formResp->createView(),
            'question_id' => $question->getId(),
            'reponses' => $reponsesRepository->findall(),
            'reponses_proposees' => $questionsRepository->findResponsesByQuestion($question->getId()),
            // 'reponses_dispo' => $questionsRepository->findRespNotSelected($question->getId()),
            'action' => 'update',
            'question' => $question
        ]);
    }

    #[Route('admin/questions/add_reponse/', name: 'question_add_resp')]
    public function add_resp(
        Request $request,
        ReponsesRepository $reponsesRepository,
        QuestionsRepository $questionsRepository
    ) {

        $data = $request->request->all();
        extract($data['reponses_type2']);
        $reponse = new Reponses();
        $reponse->setLibelle($libelle);

        if (isset($success)) {
            $reponse->setSuccess(true);
        } else {
            $reponse->setSuccess(false);
        }
        $question = $questionsRepository->find($data['question_id']);

        $question->addQuestionReponse($reponse);

        $reponsesRepository->add($reponse, true);

        $this->addFlash('success', 'La réponses a bien été ajoutée');
        return $this->redirectToRoute('question_update', ['id' => $data['question_id']]);
    }

    #[Route('admin/questions/remove_reponse/', name: 'question_remove_resp')]
    public function remove_resp(
        request $request,
        ReponsesRepository $reponsesRepository,
        QuestionsRepository $questionsRepository
    ) {
        $id_question = $request->query->get('id');
        $id_reponse = $request->query->get('id_resp');

        $reponse = $reponsesRepository->find($id_reponse);
        $questions = $questionsRepository->find($id_question);

        $questions->removeQuestionReponse($reponse);
        $reponsesRepository->remove($reponse, true);

        $this->addFlash('success', 'La reponses a bien été supprimée');
        return $this->redirectToRoute('question_update', ['id' => $id_question]);
    }

    #[Route('admin/questions/delete/{id}', name: 'question_delete')]
    public function delete(Questions $question, QuestionsRepository $questionsRepository): Response
    {
        if ($question->getImg() !== null) {
            dump($question->getImg());
            $questionImgPath = $this->getParameter('question_img_dir') . '/' . $question->getImg();

            // dd($questionImgPath);
            // On vérifie que chemin existe et que $question->getImg() n'est pas vide
            if (file_exists($questionImgPath) && !empty($question->getImg())) {
                unlink($questionImgPath);
                // if (empty($questionImgPath)) {
                //     }
            }
        }

        $questionsRepository->remove($question, true);
        $this->addFlash('success', 'La question a bien été supprimée');
        return $this->redirectToRoute('admin_questions');
    }

    // #[Route('/{id}', name: 'questions_show', methods: ['GET'])]
    // public function show(Questions $question): Response
    // {
    //     return $this->render('questions/show.html.twig', [
    //         'question' => $question,
    //     ]);
    // }
}
