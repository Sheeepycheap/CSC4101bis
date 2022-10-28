<?php

namespace App\Controller;

use App\Entity\CollectionDeVoiture;
use App\Form\CollectionDeVoitureType;
use App\Repository\CollectionDeVoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @Route("/CollectionDeVoiture")
 */
class CollectionDeVoitureController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(CollectionDeVoitureRepository $collectionDeVoitureRepository): Response
    {
        return $this->render('index.html.twig', [
            'welcome' => "Bonne utilisation de la todo list",
        ]);
    }


    /**
     * Lists all todo entities.
     *  
     * @Route("/list", name = "CollectionDeVoiture_list", methods="GET")
     * @Route("/index", name = "CollectionDeVoiture_index", methods="GET")
     */
    public function listAction(ManagerRegistry $doctrine)
    {
        $entityManager= $doctrine->getManager();
        $CollectionDeVoiture = $entityManager->getRepository(CollectionDeVoiture::class)->findAll();
        dump($CollectionDeVoiture);

    return $this->render('collection_de_voiture/index.html.twig',
        [ 'CollectionDeVoiture' => $CollectionDeVoiture ]
        );
    }



    /**
     * @Route("/new", name="app_collection_de_voiture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CollectionDeVoitureRepository $collectionDeVoitureRepository): Response
    {
        $collectionDeVoiture = new CollectionDeVoiture();
        $form = $this->createForm(CollectionDeVoitureType::class, $collectionDeVoiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collectionDeVoitureRepository->add($collectionDeVoiture, true);

            return $this->redirectToRoute('CollectionDeVoiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('collection_de_voiture/new.html.twig', [
            'collection_de_voiture' => $collectionDeVoiture,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_collection_de_voiture_show", methods={"GET"})
     */
    public function show(CollectionDeVoiture $collectionDeVoiture): Response
    {
        return $this->render('collection_de_voiture/show.html.twig', [
            'collection_de_voiture' => $collectionDeVoiture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_collection_de_voiture_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CollectionDeVoiture $collectionDeVoiture, CollectionDeVoitureRepository $collectionDeVoitureRepository): Response
    {
        $form = $this->createForm(CollectionDeVoitureType::class, $collectionDeVoiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collectionDeVoitureRepository->add($collectionDeVoiture, true);

            return $this->redirectToRoute('CollectionDeVoiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('collection_de_voiture/edit.html.twig', [
            'collection_de_voiture' => $collectionDeVoiture,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_collection_de_voiture_delete", methods={"POST"})
     */
    public function delete(Request $request, CollectionDeVoiture $collectionDeVoiture, CollectionDeVoitureRepository $collectionDeVoitureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collectionDeVoiture->getId(), $request->request->get('_token'))) {
            $collectionDeVoitureRepository->remove($collectionDeVoiture, true);
        }

        return $this->redirectToRoute('CollectionDeVoiture_index', [], Response::HTTP_SEE_OTHER);
    }
}
