<?php

namespace App\Controller;

use App\Entity\Workexperience;
use App\Form\WorkexperienceType;
use App\Repository\WorkexperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/workexperience')]
class WorkexperienceController extends AbstractController
{
    #[Route('/', name: 'app_workexperience_index', methods: ['GET'])]
    public function index(WorkexperienceRepository $workexperienceRepository): Response
    {
        return $this->render('workexperience/index.html.twig', [
            'workexperience' => $workexperienceRepository->findAll(),
        ]);
    }

      //convertir json y envio  de  informacion api
      #[Route('/apis', name: 'app_workexperience_apis', methods: ['GET'])]
      public function apis(WorkexperienceRepository $workexperienceRepository,SerializerInterface $serializer): Response
      {
          $entityManager = $this -> getDoctrine()->getManager();
          $workexperience=$entityManager->getRepository(workexperience::class)-> findall();
  
          //dump($skills); die;
  
          $json = $serializer->serialize($workexperience,"json");
          return new Response($json,Response::HTTP_OK,['Content-type' => 'application/json']);
          
      }

    #[Route('/new', name: 'app_workexperience_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WorkexperienceRepository $workexperienceRepository): Response
    {
        $workexperience = new Workexperience();
        $form = $this->createForm(WorkexperienceType::class, $workexperience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workexperienceRepository->save($workexperience, true);

            return $this->redirectToRoute('app_workexperience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workexperience/new.html.twig', [
            'workexperience' => $workexperience,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_workexperience_show', methods: ['GET'])]
    public function show(Workexperience $workexperience): Response
    {
        return $this->render('workexperience/show.html.twig', [
            'workexperience' => $workexperience,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_workexperience_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workexperience $workexperience, WorkexperienceRepository $workexperienceRepository): Response
    {
        $form = $this->createForm(WorkexperienceType::class, $workexperience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workexperienceRepository->save($workexperience, true);

            return $this->redirectToRoute('app_workexperience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workexperience/edit.html.twig', [
            'workexperience' => $workexperience,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_workexperience_delete', methods: ['POST'])]
    public function delete(Request $request, Workexperience $workexperience, WorkexperienceRepository $workexperienceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workexperience->getId(), $request->request->get('_token'))) {
            $workexperienceRepository->remove($workexperience, true);
        }

        return $this->redirectToRoute('app_workexperience_index', [], Response::HTTP_SEE_OTHER);
    }
}
