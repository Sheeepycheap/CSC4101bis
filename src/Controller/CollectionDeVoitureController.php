<?php

namespace App\Controller;

use App\Entity\CollectionDeVoiture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controleur CollectionDeVoiture   
 * @Route("/CollectionDeVoiture")
 */
class CollectionDeVoitureController extends AbstractController
{

    /**
     * @Route("/",name = "home", methods="GET")
     */
    public function indexAction(ManagerRegistry $doctrine)
    {
        return $this->render('index.html.twig',
        [ 'welcome' => "Bonne utilisation de la todo list" ]
        );
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

    return $this->render('Collection/index.html.twig',
        [ 'CollectionDeVoiture' => $CollectionDeVoiture ]
        );
    }
    
    /**
     * Finds and displays a todo entity.
     *
     * @Route("/{id}", name="Collection_show", requirements={ "id": "\d+"}, methods="GET")
     */
    public function showAction(CollectionDeVoiture $CollectionDeVoiture): Response
    {
        return $this->render('Collection/show.html.twig',
        [ 'CollectionDeVoiture' => $CollectionDeVoiture ]
        );
    }
}
