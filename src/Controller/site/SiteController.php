<?php


namespace App\Controller\site;



use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="site_", path="/site")
 */
class SiteController extends AbstractController{


    /**
     * @Route(name="listeSite", path="/site", methods={"GET"})
     */
    public function listeVille(Request $request, EntityManagerInterface $entityManager){

        $sites = $entityManager->getRepository(Site::class)->getAllSite();

        return $this->render("site/site.html.twig",["sites" => $sites]);
    }




}