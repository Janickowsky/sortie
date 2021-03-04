<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="admin_", path="/admin")
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route(path="", name="index")
     */
    public function index(): Response
    {
        $routerBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routerBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sortie manager');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Accueil', 'fa fa-home','home_home'),

            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Utilisateur', 'fa fa-users', User::class),
            MenuItem::linkToRoute('Ville', 'fa fa-university', 'ville_listeVille'),
            MenuItem::linkToRoute('Campus', 'fa fa-university', 'site_listeSite'),

        ];

    }
}
