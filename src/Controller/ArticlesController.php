<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Commentaires;
use App\Entity\CategoriesArticles;

use App\Entity\MotsClesArticles;
use App\Form\ArticlesType;
use App\Form\CommentairesType;
use App\Form\CategoriesArticlesType;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\Component\Pager\PaginatorInterface;
// use App\Form\CommentairesType;
use App\Repository\ArticlesRepository;
use App\Repository\CommentairesRepository;
use Vich\UploaderBundle\Mapping\Annotation;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response//ArticlesRepository $articlesRepository): Response
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([],['created_at' => 'desc']);
                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
                $articles = $this->getDoctrine()->getRepository(Articles::class)->findBy([],['created_at' => 'desc']);
        $articles = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6//Nombre de résultats par page
        );
        
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    

        // return $this->render('articles/index.html.twig', [
        //     'articles' => $articlesRepository->findAll(),
        // ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/new", name="articles_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUsers($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('message','Bien joué, votre article a été créé!');
            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="articles_show", methods={"GET"})
     */
    public function show($slug, Request $request): Response
    {
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
        return $this->redirectToRoute('articles_index', ['slug' => $slug]);
    }
    // Si l'article existe nous envoyons les données à la vue
    return $this->render('articles/show.html.twig', [
        'form' => $form->createView(),
        'article' => $article,
        'commentaires' => $commentaires,
    ]);

        // return $this->render('articles/show.html.twig', [
        //     'article' => $article,
        // ]);
    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="articles_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
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
