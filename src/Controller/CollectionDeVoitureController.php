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
use App\Entity\Membre;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/")
 */
class CollectionDeVoitureController extends AbstractController
{
    /**
     * @Route("/home", name="home", methods={"GET"})
     */
    public function index(CollectionDeVoitureRepository $collectionDeVoitureRepository): Response
    {   
        $em= $this->getDoctrine()->getManager();
        if ($this->isGranted('ROLE_USER')) {
            $user=$this->getUser();
            if($user){            
                $membreRepository = $em->getRepository(Membre::class);
                $membre = $membreRepository->findOneBy([
                        'user'=> $user
                ]);
                if ($membre) {
                    $membreId= $membre->getId();
                    return $this->render('index.html.twig', [
                        'membreId' => $membreId,
                    ]);
                }
                else {
                    return $this->render('index.html.twig', [
                        'membreId' => null,
                    ]);
                }
            }
        }
        else {
            return $this->render('index.html.twig', [
                'membreId' => "null",
            ]);
        }
    }


    /**
     * Lists all todo entities.
     *  
     * @Route("CollectionDeVoiture/list", name = "CollectionDeVoiture_list", methods="GET")
     * @Route("CollectionDeVoiture/index", name = "CollectionDeVoiture_index", methods="GET")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function listAction(CollectionDeVoitureRepository $collectionDeVoitureRepository)
    {   
        $em= $this->getDoctrine()->getManager();

        if ($this->isGranted('ROLE_ADMIN')) {
            $CollectionDeVoiture = $collectionDeVoitureRepository->findAll();
            return $this->render('collection_de_voiture/index.html.twig',
                [ 'CollectionDeVoiture' => $CollectionDeVoiture ]
                );
        }   

        else {
            $user=$this->getUser();
            if($user){            
                $membreRepository = $em->getRepository(Membre::class);
                $membre = $membreRepository->findOneBy([
                        'user'=> $user
                ]);
                $CollectionDeVoiture = $membre->getCollections();
                return $this->render('collection_de_voiture/index.html.twig',
                    [ 'CollectionDeVoiture' => $CollectionDeVoiture ]
                );

            }
        }
        
    }



    /**
     * @Route("CollectionDeVoiture/new/{id}", name="app_collection_de_voiture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CollectionDeVoitureRepository $collectionDeVoitureRepository, Membre $membre): Response
    {
        $collectionDeVoiture = new CollectionDeVoiture();
        $collectionDeVoiture->setMembre($membre);
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
     * @Route("CollectionDeVoiture/{id}", name="app_collection_de_voiture_show", methods={"GET"})
     */
    public function show(CollectionDeVoiture $collectionDeVoiture): Response
    {
        return $this->render('collection_de_voiture/show.html.twig', [
            'collection_de_voiture' => $collectionDeVoiture,
        ]);
    }

    /**
     * @Route("CollectionDeVoiture/{id}/edit", name="app_collection_de_voiture_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
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
     * @Route("CollectionDeVoiture/{id}", name="app_collection_de_voiture_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, CollectionDeVoiture $collectionDeVoiture, CollectionDeVoitureRepository $collectionDeVoitureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collectionDeVoiture->getId(), $request->request->get('_token'))) {
            $collectionDeVoitureRepository->remove($collectionDeVoiture, true);
        }

        return $this->redirectToRoute('CollectionDeVoiture_index', [], Response::HTTP_SEE_OTHER);
    }
}
