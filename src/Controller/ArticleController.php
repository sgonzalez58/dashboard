<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'create_article')]
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

    #[Route('/article/all', name: 'article_showAll')]
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Article::class);

        $articles = $repository -> findAll();

        if (!$repository -> findAll()) {
            throw $this->createNotFoundException(
                'No article found.'
            );
        }

        //return new Response($response);

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('liste/index.html.twig', [
            'articles' => $articles,
            'page_title' => 'Articles',
            'user' => $this->getUser(),
            'type' => 'article',
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
