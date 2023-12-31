<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/training')]
class TrainingController extends AbstractController
{
    #[Route('/', name: 'app_training_index', methods: ['GET'])]
    public function index(TrainingRepository $trainingRepository): Response
    {
        return $this->render('training/index.html.twig', [
            'trainings' => $trainingRepository->findAll(),
        ]);
    }
    #[Route('/apis', name: 'app_training_apis', methods: ['GET'])]
    public function apis(TrainingRepository $trainingRepository,SerializerInterface $serializer): Response
    {
        $entityManager = $this -> getDoctrine()->getManager();
        $training=$entityManager->getRepository(training::class)-> findall();

        //dump($skills); die;

        $json = $serializer->serialize($training,"json");
        return new Response($json,Response::HTTP_OK,['Content-type' => 'application/json']);
        
    }


    #[Route('/new', name: 'app_training_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrainingRepository $trainingRepository): Response
    {
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingRepository->save($training, true);

            return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('training/new.html.twig', [
            'training' => $training,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_show', methods: ['GET'])]
    public function show(Training $training): Response
    {
        return $this->render('training/show.html.twig', [
            'training' => $training,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Training $training, TrainingRepository $trainingRepository): Response
    {
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingRepository->save($training, true);

            return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('training/edit.html.twig', [
            'training' => $training,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_delete', methods: ['POST'])]
    public function delete(Request $request, Training $training, TrainingRepository $trainingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$training->getId(), $request->request->get('_token'))) {
            $trainingRepository->remove($training, true);
        }

        return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
    }
}
