<?php

namespace App\Controller;

use App\Entity\Voiture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
/**
 * Controleur Voiture   
 * @Route("/Voitures")
 */
class VoitureController extends AbstractController
{

    /**
     * @Route("/",name = "Voitures", methods="GET")
     */
    public function indexAction(ManagerRegistry $doctrine)
    {
        return $this->render('Voiture/index.html.twig',
        [ 'welcome' => "Bonne utilisation de la todo list" ]
        );
    }

    /**
     * Lists all todo entities.
     *
     * @Route("/list", name = "Voiture_list", methods="GET")
     * @Route("/index", name="Voiture_index", methods="GET")
     */
    public function listAction(ManagerRegistry $doctrine)
    {
        $entityManager= $doctrine->getManager();
        $Voiture = $entityManager->getRepository(Voiture::class)->findAll();
        dump($Voiture);

    return $this->render('Voiture/list.html.twig',
        [ 'Voiture' => $Voiture ]
        );
    }
    
    /**
     * Finds and displays a todo entity.
     *
     * @Route("/{id}", name="Voiture_show", requirements={ "id": "\d+"}, methods="GET")
     */
    public function showAction(Voiture $Voiture): Response
    {
        return $this->render('Voiture/show.html.twig',
        [ 'Voiture' => $Voiture ]
        );
    }
}