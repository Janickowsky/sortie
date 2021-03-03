<?php


namespace App\Controller\ville;



use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="ville_", path="/ville")
 */
class VilleController extends AbstractController{

    /**
     * @Route(name="listeVille", path="/ville", methods={"GET"})
     */
    public function listeVille(Request $request, EntityManagerInterface $entityManager){

        $villes = $entityManager->getRepository(Ville::class)->getAllVille();

        return $this->render("ville/ville.html.twig",["villes" => $villes]);
    }

    /**
     * @Route(name="creerVille", path="/creerVille, methods={"GET", "POST"})
     */
    public function creerVille(Request $request, EntityManagerInterface $entityManager){
        $ville = new Ville();
    }
}