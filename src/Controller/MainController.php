<?php

namespace App\Controller;

use App\Entity\Articles;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Articles::class)->findAll();
        return $this->render('main/index.html.twig', [
            'articles'=> $articles,

            'controller_name' => 'MainController',
        ]);
    }

    //  /**
    //  * @Route("/mentions-legales", name="mentions")
    //  */
    // public function mentions(): Response
    // {
    //     return $this->render('main/index.html.twig', [
    //         'controller_name' => 'MainController',
    //     ]);
    // }
}
