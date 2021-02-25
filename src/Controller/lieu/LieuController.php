<?php


namespace App\Controller\lieu;


use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="lieu_", path="/lieu")
 */
class LieuController extends AbstractController{


    /**
     * @Route(name="listeLieux", path="/lieu", methods={"GET"})
     */
    public function listeLieux(Request $request, EntityManagerInterface $entityManager){



        return $this->render("lieu/lieu.html.twig");
    }




}