<?php

namespace App\Controller;

use App\Entity\CategoriesArticles;
use App\Form\CategoriesArticlesType;
use App\Repository\CategoriesArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories/articles")
 */
class CategoriesArticlesController extends AbstractController
{
    /**
     * @Route("/", name="categories_articles_index", methods={"GET"})
     */
    public function index(CategoriesArticlesRepository $categoriesArticlesRepository): Response
    {
        return $this->render('categories_articles/index.html.twig', [
            'categories_articles' => $categoriesArticlesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categories_articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoriesArticle = new CategoriesArticles();
        $form = $this->createForm(CategoriesArticlesType::class, $categoriesArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoriesArticle);
            $entityManager->flush();

            return $this->redirectToRoute('categories_articles_index');
        }

        return $this->render('categories_articles/new.html.twig', [
            'categories_article' => $categoriesArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{categories}", name="categories_articles_show", methods={"GET"})
     */
    public function show(CategoriesArticles $categoriesArticle): Response
    {
        return $this->render('categories_articles/show.html.twig', [
            'categories_article' => $categoriesArticle,
        ]);
    }

    /**
     * @Route("/{categories}/edit", name="categories_articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoriesArticles $categoriesArticle): Response
    {
        $form = $this->createForm(CategoriesArticlesType::class, $categoriesArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categories_articles_index');
        }

        return $this->render('categories_articles/edit.html.twig', [
            'categories_article' => $categoriesArticle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{categories}", name="categories_articles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CategoriesArticles $categoriesArticle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoriesArticle->getCategories(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoriesArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categories_articles_index');
    }
}
