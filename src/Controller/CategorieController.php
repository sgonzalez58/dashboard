<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\Categorie;
use App\Form\AddCategorieFormType;
use App\Form\DeleteCategorieFormType;
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

            return $this->redirectToRoute('categorie_showAll');
        }

        return $this->render('categorie/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Add categorie'
        ]);
    }


    #[Route('/categorie/delete/{id}', name: 'categorie_delete')]
    public function delete(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        
        $entityManager = $doctrine->getManager();
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        $form = $this->createForm(DeleteCategorieFormType::class, $categorie);
        $form->handleRequest($request);

        if (!$categorie) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
        
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->remove($categorie);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('categorie_showAll');
        }

        //return new Response('Check out this great categorie: '.$categorie->getNom());

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('categorie/delete.html.twig', [
            'deleteCategorieForm' => $form->createView(),
            'my_title' => 'Delete categorie n°'.$id.', '.$categorie->getNom().' ?',
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
            $categorie->setNom($form->get('nom')->getData());
            
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->persist($categorie);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('categorie_showAll');
        }

        //return new Response('Check out this great categorie: '.$categorie->getNom());

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('categorie/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Update categorie',
        ]);
    }


    #[Route('/categorie', name: 'categorie_showAll')]
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Categorie::class);

        $categories = $repository -> findAll();

        $maxCategories = count($categories);

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

        $maxPages = ceil($maxCategories / $max);
        

        if (!$repository -> findAll()) {
            throw $this->createNotFoundException(
                'No article found.'
            );
        }

        //return new Response($response);

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('categorie/index.html.twig', [
            'articles' => $myPage,
            'nbPageMax' =>  (string)$maxPages,
            'page_title' => 'Categories',
            'user' => $this->getUser(),
            'type' => 'categorie',
            'max_product' => $max,
            'page' => $page,
            'tri' => $tri,
            'sens' => $sens,
        ]);
    }
}
