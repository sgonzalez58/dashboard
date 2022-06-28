<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


use App\Entity\LieuAchat;
use App\Form\AddLieuAchatFormType;
use App\Form\DeleteLieuAchatFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class LieuAchatController extends AbstractController
{
    #[Route('/lieuAchat/add', name: 'create_lieuAchat')]
    public function createLieuAchat(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $lieuAchat = new LieuAchat();
        $form = $this->createForm(AddLieuAchatFormType::class, $lieuAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nouveau_nom = $form->get('nom')->getData();
            $lieuAchat->setNom($nouveau_nom);
        
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->persist($lieuAchat);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new lieuAchat with id '.$lieuAchat->getId());
        }

        return $this->render('lieuAchat/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Add lieuAchat'
        ]);
    }

    #[Route('/lieuAchat/all', name: 'lieuAchat_showAll')]
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(LieuAchat::class);

        $lieuxAchats = $repository -> findAll();

        if (!$repository -> findAll()) {
            throw $this->createNotFoundException(
                'No article found.'
            );
        }

        //return new Response($response);

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('liste/index.html.twig', [
            'articles' => $lieuxAchats,
            'page_title' => 'LieuxAchats',
            'user' => $this->getUser(),
            'type' => 'lieuAchat',
        ]);
    }

    #[Route('/lieuAchat/delete/{id}', name: 'lieuAchat_delete')]
    public function delete(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        
        $entityManager = $doctrine->getManager();
        $lieuAchat = $doctrine->getRepository(LieuAchat::class)->find($id);
        $form = $this->createForm(DeleteLieuAchatFormType::class, $lieuAchat);
        $form->handleRequest($request);

        if (!$lieuAchat) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
        
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->remove($lieuAchat);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('LieuAchat n°'.$id.', '.$lieuAchat->getNom().' has been deleted.');
        }

        //return new Response('Check out this great lieuAchat: '.$lieuAchat->getNom());

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('lieuAchat/delete.html.twig', [
            'deleteLieuAchatForm' => $form->createView(),
            'my_title' => 'Delete lieuAchat n°'.$id.', '.$lieuAchat->getNom().' ?',
        ]);
    }

    #[Route('/lieuAchat/{id}', name: 'lieuAchat_update')]
    public function update(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        
        $entityManager = $doctrine->getManager();
        $lieuAchat = $doctrine->getRepository(LieuAchat::class)->find($id);
        $form = $this->createForm(AddLieuAchatFormType::class, $lieuAchat);
        $form->handleRequest($request);

        if (!$lieuAchat) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuAchat->setNom($form->get('nom')->getData());
            $lieuAchat->setType($form->get('type')->getData());
            $lieuAchat->setAdresse($form->get('adresse')->getData());
            
            // tell Doctrine you want to (eventually) save the article (no queries yet)
            $entityManager->persist($lieuAchat);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved lieuAchat '.$lieuAchat->getId().'.');
        }

        //return new Response('Check out this great lieuAchat: '.$lieuAchat->getNom());

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('lieuAchat/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Update lieuAchat',
        ]);
    }
}
