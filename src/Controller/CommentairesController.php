<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Route("/commentaires")
 */
class CommentairesController extends AbstractController
{
    /**
     * @Route("/", name="commentaires_index", methods={"GET"})
     */
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commentaires_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('commentaires_index');
        }

        return $this->render('commentaires/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaires_show", methods={"GET"})
     */
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commentaires_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commentaires $commentaire): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentaires_index');
        }

        return $this->render('commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaires_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Commentaires $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commentaires_index');
    }

/**
 * @Route("/{slug}", name="article")
*/
public function article($slug, Request $request){
    // On récupère l'article correspondant au slug
    $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
    $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findBy([
        'articles' => $article,
        'actif' => 1
    ],['created_at' => 'desc']);
    if(!$article){
        // Si aucun article n'est trouvé, nous créons une exception
        throw $this->createNotFoundException('L\'article n\'existe pas');
    }

    // Nous créons l'instance de "Commentaires"
    $commentaire = new Commentaires();

    // Nous créons le formulaire en utilisant "CommentairesType" et on lui passe l'instance
    $form = $this->createForm(CommentairesType::class, $commentaire);

    // Nous récupérons les données
    $form->handleRequest($request);

    // Nous vérifions si le formulaire a été soumis et si les données sont valides
    if ($form->isSubmitted() && $form->isValid()) {
        // Hydrate notre commentaire avec l'article
        $commentaire->setArticles($article);

        // Hydrate notre commentaire avec la date et l'heure courants
        $commentaire->setCreatedAt(new \DateTime('now'));

        $doctrine = $this->getDoctrine()->getManager();

        // On hydrate notre instance $commentaire
        $doctrine->persist($commentaire);

        // On écrit en base de données
        $doctrine->flush();

        // On redirige l'utilisateur
        return $this->redirectToRoute('actualites_article', ['slug' => $slug]);
    }
    // Si l'article existe nous envoyons les données à la vue
    return $this->render('articles/article.html.twig', [
        'form' => $form->createView(),
        'article' => $article,
        'commentaires' => $commentaires,
    ]);
}
}
