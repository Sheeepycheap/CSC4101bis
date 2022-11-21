<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\Voiture1Type;
use App\Entity\Membre;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/voiture")
 */
class VoitureController extends AbstractController
{
    /**
     * @Route("/", name="app_voiture_index", methods={"GET"})
     * @Route("/", name = "Voiture_list", methods="GET")
     */
    public function index(VoitureRepository $voitureRepository): Response
    {      

        $em= $this->getDoctrine()->getManager();
        //$voitures = $em->getRepository(Voiture::class)->findAll();
        if ($this->isGranted('ROLE_ADMIN')) {
            $voitures = $voitureRepository->findAll();
            return $this->render('voiture/index.html.twig',
                [ 'voitures' => $voitures ]
            );
        }
        else {
            $user=$this->getUser();
            if($user){            
                $membreRepository = $em->getRepository(Membre::class);
                $membre = $membreRepository->findOneBy([
                        'user'=> $user
                ]);
                $voitures = $voitureRepository->findMemberVoitures($membre);
                return $this->render('voiture/index.html.twig',
                [ 'voitures' => $voitures ]
            );
            }
        }
    }

    /**
     * @Route("/new", name="app_voiture_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, VoitureRepository $voitureRepository): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(Voiture1Type::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->add($voiture, true);

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/new.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_voiture_show", methods={"GET"})
     */
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_voiture_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        $form = $this->createForm(Voiture1Type::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->add($voiture, true);

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/edit.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_voiture_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voiture->getId(), $request->request->get('_token'))) {
            $voitureRepository->remove($voiture, true);
        }

        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
}
