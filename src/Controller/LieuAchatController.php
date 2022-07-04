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

            return $this->redirectToRoute('lieuAchat_showAll');
        }

        return $this->render('lieuAchat/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Add lieuAchat'
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

            return $this->redirectToRoute('lieuAchat_showAll');
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

            return $this->redirectToRoute('lieuAchat_showAll');
        }

        //return new Response('Check out this great lieuAchat: '.$lieuAchat->getNom());

        // or render a template
        // in the template, print things with {{ article.name }}
        return $this->render('lieuAchat/add.html.twig', [
            'registrationForm' => $form->createView(),
            'my_title' => 'Update lieuAchat',
        ]);
    }

    #[Route('/lieuAchat', name: 'lieuAchat_showAll')]
    public function showAll(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(LieuAchat::class);

        $lieuxAchats = $repository -> findAll();

        $maxLieux = count($lieuxAchats);

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

        $maxPages = ceil($maxLieux / $max);

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
            'page_title' => 'LieuxAchats',
            'user' => $this->getUser(),
            'type' => 'lieuAchat',
            'max_product' => $max,
            'page' => $page,
            'tri' => $tri,
            'sens' => $sens,
        ]);
    }
}
