<?php

namespace App\Controller\Admin;


use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\CollectionDeVoiture;
use App\Entity\Voiture;
use App\Entity\Membre;
use App\Entity\Galerie;  


class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */

    public function index(): Response
    {        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(CollectionDeVoitureCrudController::class)->generateUrl());
    }
    /*
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(CollectionDeVoitureCrudController::class)->generateUrl();
        return $this->redirect($url);
    }
    */

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Vroooooom');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fas fa-home');
        yield MenuItem::linkToCrud('CollectionDeVoiture', 'fas fa-door-closed', CollectionDeVoiture::class);
        yield MenuItem::linkToCrud('Voitures', 'fas fa-car', Voiture::class);
        yield MenuItem::linkToCrud('Membre', 'fas fa-user', Membre::class);
        yield MenuItem::linkToCrud('Galerie', 'fas fa-art', Galerie::class);

    }
}
