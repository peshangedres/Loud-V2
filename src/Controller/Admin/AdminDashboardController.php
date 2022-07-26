<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Entity\Administrator;
use App\Entity\AdministratorRole;
use App\Entity\Category;
use App\Entity\Content;
use App\Entity\Event;
use App\Entity\EventGame;
use App\Entity\Member;
use App\Entity\Nationality;
use App\Entity\News;
use App\Entity\Product;
use App\Entity\Role;
use App\Entity\Section;
use App\Entity\Social;
use App\Entity\Sponsor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $role = '';
        if ($this->getUser()) {
            $role = implode('', $this->getUser()->getRoles());
        }

        $routeBuilder = $this->get(AdminUrlGenerator::class);

        if ($role == 'ROLE_GLOBAL') {
            return $this->redirect($routeBuilder->setController(AdministratorCrudController::class)->generateUrl());
        }

        if ($role == 'ROLE_ESPORT') {
            return $this->redirect($routeBuilder->setController(MemberCrudController::class)->generateUrl());
        }

        if ($role == 'ROLE_COMMUNICATION') {
            return $this->redirect($routeBuilder->setController(NewsCrudController::class)->generateUrl());
        }

        if ($role == 'ROLE_EVENEMENTIEL') {
            return $this->redirect($routeBuilder->setController(EventCrudController::class)->generateUrl());
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Espace administrateur - Loud')
            ->setFaviconPath('/assets/img/logo/favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        $role = '';
        if ($this->getUser()) {
            $role = implode('', $this->getUser()->getRoles());
        }
        yield MenuItem::linktoDashboard('Accueil', 'fa fa-home');
        if ($role == 'ROLE_GLOBAL') {
            yield MenuItem::section('Administration');
            yield MenuItem::linkToCrud('Administrateurs', 'fas fa-user', Administrator::class);
            yield MenuItem::linkToCrud('Réseaux', 'fas fa-globe', Social::class);
            yield MenuItem::linkToCrud('Contenu', 'fas fa-align-left', Content::class);
            yield MenuItem::linkToCrud('Publicités', 'fas fa-ad', Ad::class);
            yield MenuItem::linkToCrud('Sections', 'fas fa-users', Section::class);
            yield MenuItem::linkToCrud('Rôles', 'fas fa-crown', Role::class);
            yield MenuItem::linkToCrud('Nationalités', 'fas fa-globe', Nationality::class);
            yield MenuItem::linkToCrud('Sponsors', 'fas fa-money-bill-wave', Sponsor::class);
            yield MenuItem::linkToCrud('Produits', 'fas fa-tshirt', Product::class);
        }
        if ($role == 'ROLE_ESPORT' || $role == 'ROLE_GLOBAL') {
            yield MenuItem::section('Esport');
            yield MenuItem::linkToCrud('Membres', 'fas fa-user', Member::class);
        }
        if ($role == 'ROLE_COMMUNICATION' || $role == 'ROLE_GLOBAL') {
            yield MenuItem::section('Communication');
            yield MenuItem::linkToCrud('Catégories', 'fas fa-folder', Category::class);
            yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', News::class);
        }
        if ($role == 'ROLE_EVENEMENTIEL' || $role == 'ROLE_GLOBAL') {
            yield MenuItem::section('Évènementiel');
            yield MenuItem::linkToCrud('Jeux', 'fas fa-gamepad', EventGame::class);
            yield MenuItem::linkToCrud('Tournois', 'fas fa-trophy', Event::class);
        }
    }
}
