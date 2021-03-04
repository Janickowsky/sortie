<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
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
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sortie');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Accueil', 'fa fa-home'),

            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Utilisateur', 'fa fa-tags', User::class),
            //MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),

        ];

    }
}
