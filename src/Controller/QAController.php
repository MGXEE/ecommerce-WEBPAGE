<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class QAController extends AbstractController
{
    #[Route('/q/a', name: 'app_q_a')]
    public function index(): Response
    {
        return $this->render('qa/index.html.twig', [
            'controller_name' => 'QAController',
        ]);
    }

    #[Route('/q/a/form', name: 'app_q_a_form', methods: ['GET', 'POST'])]
    public function form(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $questionText = $request->request->get('question');
            $email = $request->request->get('email');

            $question = new Question();
            $question->setEmail($email);
            $question->setQuestions($questionText);

            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'We received your question, we will answer as soon as possible!');

            return $this->redirectToRoute('app_q_a');
        }

        return $this->render('qa/form.html.twig');
    }

    #[Route('/q/a/page', name: 'app_q_a_page')]
    public function page(\App\Repository\QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findAll();

        return $this->render('qa/page.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/q/a/delete/{id}', name: 'app_q_a_delete', methods: ['POST'])]
    public function delete(int $id, QuestionRepository $questionRepository, EntityManagerInterface $em): RedirectResponse
    {
        $question = $questionRepository->find($id);

        if ($question) {
            $em->remove($question);
            $em->flush();
            $this->addFlash('success', 'Question deleted successfully.');
        } else {
            $this->addFlash('error', 'Question not found.');
        }

        return $this->redirectToRoute('app_q_a_page');
    }
}
