<?php

namespace App\Controller;

use App\Entity\Userportfolio;
use App\Form\UserportfolioType;
use App\Repository\UserportfolioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/userportfolio')]
class UserportfolioController extends AbstractController
{
    #[Route('/', name: 'app_userportfolio_index', methods: ['GET'])]
    public function index(UserportfolioRepository $userportfolioRepository): Response
    {
        return $this->render('userportfolio/index.html.twig', [
            'userportfolios' => $userportfolioRepository->findAll(),
        ]);
    }


    #[Route('/apis', name: 'app_userportfolio_apis', methods: ['GET'])]
    public function apis(UserportfolioRepository $userportfolioRepository,SerializerInterface $serializer): Response
    {
        $entityManager = $this -> getDoctrine()->getManager();
        $userPortfolio=$entityManager->getRepository(userportfolio::class)-> findall();

        //dump($skills); die;

        $json = $serializer->serialize($userPortfolio,"json");
        return new Response($json,Response::HTTP_OK,['Content-type' => 'application/json']);
        
    }

    #[Route('/new', name: 'app_userportfolio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserportfolioRepository $userportfolioRepository): Response
    {
        $userportfolio = new Userportfolio();
        $form = $this->createForm(UserportfolioType::class, $userportfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userportfolioRepository->save($userportfolio, true);

            return $this->redirectToRoute('app_userportfolio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('userportfolio/new.html.twig', [
            'userportfolio' => $userportfolio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_userportfolio_show', methods: ['GET'])]
    public function show(Userportfolio $userportfolio): Response
    {
        return $this->render('userportfolio/show.html.twig', [
            'userportfolio' => $userportfolio,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_userportfolio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Userportfolio $userportfolio, UserportfolioRepository $userportfolioRepository): Response
    {
        $form = $this->createForm(UserportfolioType::class, $userportfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userportfolioRepository->save($userportfolio, true);

            return $this->redirectToRoute('app_userportfolio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('userportfolio/edit.html.twig', [
            'userportfolio' => $userportfolio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_userportfolio_delete', methods: ['POST'])]
    public function delete(Request $request, Userportfolio $userportfolio, UserportfolioRepository $userportfolioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userportfolio->getId(), $request->request->get('_token'))) {
            $userportfolioRepository->remove($userportfolio, true);
        }

        return $this->redirectToRoute('app_userportfolio_index', [], Response::HTTP_SEE_OTHER);
    }
}
