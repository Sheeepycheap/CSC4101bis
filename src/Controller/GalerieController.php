<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Entity\Membre;
use App\Entity\Voiture;
use App\Entity\User;
use App\Form\GalerieType;
use App\Repository\GalerieRepository;
use App\Repository\MembreRepository;
use PhpParser\Builder\Class_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManager;

/**
 * @Route("/galerie")
 */
class GalerieController extends AbstractController
{
    /**
     * @Route("/", name="app_galerie_index", methods={"GET"})
     */
    public function index(GalerieRepository $galerieRepository): Response
    {   
        
        $privateGaleries = array();
        // Si on est admin, on voit tout :
        if($this->isGranted('ROLE_ADMIN')){
            return $this->render('galerie/index.html.twig',[
                'galeries' => $galerieRepository->findAll(),
                'creator' => null
            ]);
        }
        // Si on est pas Admin : 
        else{  
            $user=$this->getUser();
            $publicGaleries = $galerieRepository->findBy([
                'published' => true,
            ]);
            // Si on est User simple authentifié : 
            if($user){
                //$useremail=$user->getUserIdentifier();
                $em=$this->getDoctrine()->getManager();
                $membreRepository = $em->getRepository(Membre::class);
                $membre = $membreRepository->findOneBy([
                    'user'=> $user
                ]);
                //dd($membre);
                $privateGaleries = $galerieRepository->findBy([
                    'published' => false,
                    'creator' => $membre
                ]);

                return $this->render('galerie/index.html.twig',[
                    'galeries' => array_merge($privateGaleries,$publicGaleries),
                    'creator' => $membre
                ]);
            }
            else {
                return $this->render('galerie/index.html.twig',[
                    'galeries' => $publicGaleries,
                    'creator' => false
                ]);
            }
        }
        }

    /**
     * @Route("/new", name="app_galerie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GalerieRepository $galerieRepository): Response
    {
        $galerie = new Galerie();
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galerieRepository->add($galerie, true);

            return $this->redirectToRoute('app_galerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('galerie/new.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_galerie_show", methods={"GET"})
     */
    public function show(Galerie $galerie): Response
    {
        return $this->render('galerie/show.html.twig', [
            'galerie' => $galerie,
        ]);
    }

    /**
     * @Route("/{galerie_id}/Voiture/{Voiture_id}", name="app_galerie_Voiture_show", methods={"GET"})
     * @ParamConverter("galerie", options={"id" = "galerie_id"})
     * @ParamConverter("voiture", options={"id" = "Voiture_id"})
     */

    public function voitureShow(Galerie $galerie, Voiture $voiture): Response
    {
        if(! $galerie->getVoiture()->contains($voiture)) {
            throw $this->createNotFoundException("Couldn't find such a voiture in this galerie!");
        }

        if(! $galerie->isPublished()) {
            throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        }

        return $this->render('galerie/voiture_show.html.twig', [
            'voiture' => $voiture,
            'galerie' => $galerie
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_galerie_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Galerie $galerie, GalerieRepository $galerieRepository): Response
    {
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galerieRepository->add($galerie, true);

            return $this->redirectToRoute('app_galerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('galerie/edit.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_galerie_delete", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Galerie $galerie, GalerieRepository $galerieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$galerie->getId(), $request->request->get('_token'))) {
            $galerieRepository->remove($galerie, true);
        }

        return $this->redirectToRoute('app_galerie_index', [], Response::HTTP_SEE_OTHER);
    }
}
