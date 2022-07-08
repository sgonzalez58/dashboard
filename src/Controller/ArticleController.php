<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\LieuAchat;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AddArticleFormType;
use App\Form\DeleteArticleFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use App\Form\rechercheType;

class ArticleController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/article/add', name: 'create_article')]
    public function createarticle(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $article = new Article();
        
        $form = $this->createForm(AddArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $fichier = $form->get('fichier_photo')->getData();

            if($fichier){

                $nomFichier = $fichier->getClientOriginalName();
                $fichier->move(
                    $this->getParameter('images_directory'),
                    $nomFichier, 
                );

                $article->setPhoto($nomFichier);
                // tell Doctrine you want to (eventually) save the article (no queries yet)
                $entityManager->persist($article);
    
                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
    
                return $this->redirectToRoute('article_showAll');
            }else{
                return New Response('<html><body>Le fichier n\'a pas été ajouté. Veuillez ajouter une photo.</body></html>');
            }
        }
        return $this->render('article/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Add article'
        ]);
    }

    #[Route('/article', name: 'article_showAll')]
    public function showAll(Request $request, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Article::class);

        $article = new Article();
        $form = $this->createForm(rechercheType::class, $article);
        $form->handleRequest($request);

        $categories = $doctrine->getRepository(Categorie::class);

        $lieux = $doctrine->getRepository(LieuAchat::class);

        $conditions = array();

        $session = $this->requestStack->getSession();

        $request = Request::createFromGlobals();

        $nom = $request->query->get('search');
        $max = $request->query->get('max');
        $page = $request->query->get('page');
        $tri = $request->query->get('tri');
        $categorie = $request->query->get('categorie');
        $lieu = $request->query->get('lieu');
        $distance = $request->query->get('distance');
        $garantie = $request->query->get('garantie');
        $apres = $request->query->get('apres');
        $avant = $request->query->get('avant');
        $sup = $request->query->get('sup');
        $inf = $request->query->get('inf');

        if(isset($max)){
            $session->set('max', $max);
            $session->remove('page');
        }

        if(isset($page)){
            $session->set('page', $page);
        }

        if(isset($tri)){
            if($session->get('tri', 'id') == $tri)
                if($session->get('sens', 'ASC') == 'ASC'){
                    $session->set('sens', 'DESC');
                }else{
                    $session->set('sens', 'ASC');
                }
            else{
                $session->set('tri', $tri);
                $session->remove('sens');
            }
            $session->remove('page');
        }

        if(!isset($categorie) || $categorie == ''){
            $categorie = '';
        }else{
            $conditions = array_merge($conditions, array_fill_keys(['categorie'], $categorie));
        }

        if(!isset($lieu) || $lieu == ''){
            $lieu = '';
            if(!isset($distance) || $distance == ''){
                $distance = '';
            }else{
                $mesLieux = $lieux->findBy(['type' => $distance]);
                $conditions = array_merge($conditions, array_fill_keys(['lieu_achat'], $mesLieux));
            }
        }else{
            $conditions = array_merge($conditions, array_fill_keys(['lieu_achat'], $lieu));
            if(!isset($distance) || $distance == ''){
                $distance = '';
            }
        }

        if(!isset($garantie) || $garantie == ''){
            $garantie = '';
        }else{
            $conditions = array_merge($conditions, array_fill_keys(['date_garantie'], $garantie));
        }
        if(!isset($apres) || $apres == ''){
            $apres = '';
            if(!isset($avant) || $avant == ''){
                $avant = '';
            }else{
                $conditions = array_merge($conditions, array_fill_keys(['date_achat'], ['avant', $avant]));
            }
        }else{
            if(!isset($avant) || $avant == ''){
                $avant = '';
                $conditions = array_merge($conditions, array_fill_keys(['date_achat'], ['apres', $apres]));
            }else{
                $conditions = array_merge($conditions, array_fill_keys(['date_achat'], ['entre', $apres, $avant]));
            }
        }

        if(!isset($sup) || $sup == ''){
            $sup = '';
            if(!isset($inf) || $inf == ''){
                $inf = '';
            }else{
                $conditions = array_merge($conditions, array_fill_keys(['prix'], ['inf', $inf]));
            }
        }else{
            if(!isset($inf) || $inf == ''){
                $inf = '';
                $conditions = array_merge($conditions, array_fill_keys(['prix'], ['sup', $sup]));
            }else{
                $conditions = array_merge($conditions, array_fill_keys(['prix'], ['entre', $sup, $inf]));
            }
        }

        if(isset($nom)){
            $session->set('nom', $nom);
            $session->remove('page');
        }

        $myPage = $repository ->search(
            $session->get('nom', ''),
            $conditions,
            $session->get('tri', 'id'),
            $session->get('sens', 'ASC'),
            $session->get('max', 25),
            $session->get('max', 25) * ($session->get('page', 1) - 1),
        );

        $articles = $repository ->search(
            $session->get('nom', ''),
            $conditions
        );
        
        $maxArticles = count($articles);

        $maxPages = ceil($maxArticles / $session->get('max', 25));

        //return new Response($response);

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('liste/index.html.twig', [
            'articles' => $myPage,
            'nbPageMax' =>  (string)$maxPages,
            'page_title' => 'Articles',
            'user' => $this->getUser(),
            'type' => 'article',
            'max_product' => $session->get('max', '25'),
            'page' => $session->get('page', '1'),
            'tri' => $session->get('tri', 'id'),
            'sens' => $session->get('sens', 'ASC'),
            'form' => $form,
            'nom' => $session->get('nom'),
            'categories' => $categories,
            'categorie' => $categorie,
            'lieuxachats' => $lieux,
            'lieu' => $lieu,
            'distance' => $distance,
            'garantie' => $garantie,
            'apres' => $apres,
            'avant' => $avant,
            'sup' => $sup,
            'inf' => $inf,
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'images_directory' => 'uploads/'.$article->getPhoto()
        ]);
    }

    #[Route("/article/delete/{id}", name: "dashboard_delete")]
    public function delete(Request $request, ManagerRegistry $managerRegistry, int $id)
    {
        $entityManager = $managerRegistry->getManager();
        $article = $managerRegistry->getRepository(Article::class)->find($id);
        $form = $this->createForm(DeleteArticleFormType::class, $article);
        $form->handleRequest($request);
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($article);

            $entityManager->flush();

            return $this->redirectToRoute('article_showAll');
        }

        return $this->render('article/delete.html.twig', [
            'formArticle' => $form->createView(),
            'my_title' => $article->getNom(),
        ]);
    }

    #[Route("/article/modifier/{id}", name: "dashboard_modifier")]
    public function modifier(Request $request, ManagerRegistry $managerRegistry, int $id)
    {
        $entityManager = $managerRegistry->getManager();
        $article = $managerRegistry->getRepository(Article::class)->find($id);
        $form = $this->createForm(AddArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $fichier = $form->get('fichier_photo')->getData();

            if($fichier){

                $nomFichier = $fichier->getClientOriginalName();
                $fichier->move(
                    $this->getParameter('images_directory'),
                    $nomFichier, 
                );
                $article->setPhoto($nomFichier);  
            }
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->persist($article);
    
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('article_showAll');
        }
        return $this->render('article/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Modifier '.$article->getNom(),
        ]);
    }
}
