<?php

namespace App\Controller;

use App\Entity\MotsClesArticles;
use App\Form\MotsClesArticlesType;
use App\Repository\MotsClesArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mots/cles/articles")
 */
class MotsClesArticlesController extends AbstractController
{
    /**
     * @Route("/", name="mots_cles_articles_index", methods={"GET"})
     */
    public function index(MotsClesArticlesRepository $motsClesArticlesRepository): Response
    {
        return $this->render('mots_cles_articles/index.html.twig', [
            'mots_cles_articles' => $motsClesArticlesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mots_cles_articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $motsClesArticle = new MotsClesArticles();
        $form = $this->createForm(MotsClesArticlesType::class, $motsClesArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($motsClesArticle);
            $entityManager->flush();

            return $this->redirectToRoute('mots_cles_articles_index');
        }

        return $this->render('mots_cles_articles/new.html.twig', [
            'mots_cles_article' => $motsClesArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{mots_cles}", name="mots_cles_articles_show", methods={"GET"})
     */
    public function show(MotsClesArticles $motsClesArticle): Response
    {
        return $this->render('mots_cles_articles/show.html.twig', [
            'mots_cles_article' => $motsClesArticle,
        ]);
    }

    /**
     * @Route("/{mots_cles}/edit", name="mots_cles_articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MotsClesArticles $motsClesArticle): Response
    {
        $form = $this->createForm(MotsClesArticlesType::class, $motsClesArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mots_cles_articles_index');
        }

        return $this->render('mots_cles_articles/edit.html.twig', [
            'mots_cles_article' => $motsClesArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{mots_cles}", name="mots_cles_articles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MotsClesArticles $motsClesArticle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$motsClesArticle->getMots_cles(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($motsClesArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mots_cles_articles_index');
    }
}
