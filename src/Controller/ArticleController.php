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

use App\Form\rechercheType;

class ArticleController extends AbstractController
{
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

        $request = Request::createFromGlobals();

        $nom = $request->query->get('search');
        $max = $request->query->get('max');
        $page = $request->query->get('page');
        $tri = $request->query->get('tri');
        $sens = $request->query->get('sens');
        $categorie = $request->query->get('categorie');
        $lieu = $request->query->get('lieu');
        $distance = $request->query->get('distance');

        if(!isset($max)){
            $max = '25';
        }
        if(!isset($page)){
            $page = '1';
        }
        if(!isset($tri)){
            $tri = 'id';
        }
        if(!isset($sens)){
            $sens = 'ASC';
        }
        if(!isset($categorie) || $categorie == ''){
            $categorie = '';
        }else{
            $conditions = array_merge($conditions, array_fill_keys(['categorie'], $categorie));
        }
        if(!isset($lieu) || $lieu == ''){
            $lieu = '';
        }else{
            $conditions = array_merge($conditions, array_fill_keys(['lieu_achat'], $lieu));
        }
        if(!isset($distance) || $distance == ''){
            $distance = '';
        }else{
            $mesLieux = $lieux->findBy(['type' => $distance]);
            $conditions = array_merge($conditions, array_fill_keys(['lieu_achat'], $mesLieux));
        }

        if(isset($nom) && $nom != ''){
            $myPage = $repository ->search(
                $nom,
                $conditions,
                $tri,
                $sens,
                $max,
                $max * ($page - 1),
            );

            $articles = $repository ->search(
                $nom,
                $conditions
            );
        }else{
            $nom = '';
            $myPage = $repository -> findBy(
                $conditions,
                array($tri => $sens),
                $max,
                $max * ($page - 1),
            );

            $articles = $repository ->findBy(
                $conditions,
            );
        }

        $maxArticles = count($articles);

        $maxPages = ceil($maxArticles / $max);

        //return new Response($response);

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('liste/index.html.twig', [
            'articles' => $myPage,
            'nbPageMax' =>  (string)$maxPages,
            'page_title' => 'Articles',
            'user' => $this->getUser(),
            'type' => 'article',
            'max_product' => $max,
            'page' => $page,
            'tri' => $tri,
            'sens' => $sens,
            'form' => $form,
            'nom' => $nom,
            'categories' => $categories,
            'categorie' => $categorie,
            'lieuxachats' => $lieux,
            'lieu' => $lieu,
            'distance' => $distance
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
