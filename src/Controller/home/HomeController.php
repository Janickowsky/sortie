<?php

namespace App\Controller\home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="home_", path="")
 */
class HomeController extends AbstractController{

    /**
     * @Route(name="home", path="", methods={"GET"})
     */
    public function home(){
        return $this->redirectToRoute('sortie_listeSorties');
    }

}