<?php

namespace App\Controller;

use App\Entity\MotsCles;
use App\Form\MotsClesType;
use App\Repository\MotsClesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mots/cles")
 */
class MotsClesController extends AbstractController
{
    /**
     * @Route("/", name="mots_cles_index", methods={"GET"})
     */
    public function index(MotsClesRepository $motsClesRepository): Response
    {
        return $this->render('mots_cles/index.html.twig', [
            'mots_cles' => $motsClesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mots_cles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $motsCle = new MotsCles();
        $form = $this->createForm(MotsClesType::class, $motsCle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($motsCle);
            $entityManager->flush();

            return $this->redirectToRoute('mots_cles_index');
        }

        return $this->render('mots_cles/new.html.twig', [
            'mots_cle' => $motsCle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mots_cles_show", methods={"GET"})
     */
    public function show(MotsCles $motsCle): Response
    {
        return $this->render('mots_cles/show.html.twig', [
            'mots_cle' => $motsCle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mots_cles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MotsCles $motsCle): Response
    {
        $form = $this->createForm(MotsClesType::class, $motsCle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mots_cles_index');
        }

        return $this->render('mots_cles/edit.html.twig', [
            'mots_cle' => $motsCle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mots_cles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MotsCles $motsCle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$motsCle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($motsCle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mots_cles_index');
    }
}
