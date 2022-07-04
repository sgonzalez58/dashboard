<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    #[Route('/article/add', name: 'create_article')]
    public function createarticle(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $article = new Article();
        $article -> setLieuAchat(1);
        $article -> setNom('Clavier');
        $article -> setCategorie(1);
        $article -> setDateAchat(new \DateTime('2021-10-20 14:12:21'));
        $article -> setDateGarantie(new \DateTime('2022-04-20'));
        $article -> setPrix(250);
        $article -> setPhoto("ClavierTicketAchat.jpg");

        // tell Doctrine you want to (eventually) save the article (no queries yet)
        $entityManager->persist($article);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new article with id '.$article->getId());
    }

    #[Route('/article', name: 'article_showAll')]
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Article::class);

        $articles = $repository -> findAll();

        $maxArticles = count($articles);

        $request = Request::createFromGlobals();

        $max = $request->query->get('max');
        $page = $request->query->get('page');
        $tri = $request->query->get('tri');
        $sens = $request->query->get('sens');

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

        $myPage = $repository -> findBy(
            array(),
            array($tri => $sens),
            $max,
            $max * ($page - 1),
        );

        $maxPages = ceil($maxArticles / $max);

        if (!$repository -> findAll()) {
            throw $this->createNotFoundException(
                'No article found.'
            );
        }

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
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $article = $doctrine->getRepository(Article::class)->find($id);
        

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        return new Response('Check out this great article: '.$article->getNom());

        // or render a template
        // in the template, print things with {{ article.name }}
        // return $this->render('article/show.html.twig', ['article' => $article]);
    }
}
