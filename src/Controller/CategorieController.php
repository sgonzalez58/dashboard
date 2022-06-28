<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Categorie;
use App\Form\AddCategorieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class CategorieController extends AbstractController
{
    #[Route('/categorie/add', name: 'create_categorie')]
    public function createcategorie(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $categorie = new Categorie();
        $form = $this->createForm(AddCategorieFormType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouveau_nom = $form->get('nom')->getData();
            $categorie->setNom($nouveau_nom);
        
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->persist($categorie);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new categorie with id '.$categorie->getId());
        }

        return $this->render('categorie/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Add categorie'
        ]);
    }

    #[Route('/categorie/all', name: 'categorie_showAll')]
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Categorie::class);

        $categories = $repository -> findAll();

        if (!$repository -> findAll()) {
            throw $this->createNotFoundException(
                'No article found.'
            );
        }

        //return new Response($response);

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('liste/index.html.twig', [
            'articles' => $categories,
            'page_title' => 'Categories',
            'user' => $this->getUser(),
            'type' => 'categorie',
        ]);
    }

    #[Route('/categorie/{id}', name: 'categorie_update')]
    public function update(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        
        $entityManager = $doctrine->getManager();
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(AddCategorieFormType::class, $categorie);
        $form->handleRequest($request);

        if (!$categorie) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $nouveau_nom = $form->get('nom')->getData();
            $categorie->setNom($nouveau_nom);
        
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->persist($categorie);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved categorie '.$categorie->getId().' with new name : '.$categorie->getNom());
        }

        //return new Response('Check out this great categorie: '.$categorie->getNom());

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('categorie/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Update categorie',
        ]);
    }
}
