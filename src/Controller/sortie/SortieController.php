<?php


namespace App\Controller\sortie;


use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="sortie_", path="/sortie")
 */
class SortieController extends AbstractController{

    /**
     * @Route(name="listeSorties", path="/sorties", methods={"GET"})
     */
    public function listeSorties(Request $request, EntityManagerInterface $entityManager){

        $sorties = $entityManager->getRepository(Sortie::class)->getAllSortie();

        return $this->render("sortie/sortie.html.twig",["sorties" => $sorties]);
    }

    /**
     * @Route(name="creerSortie", path="/creersorties", methods={"GET", "POST"})
     */
    public function creerSorties(Request $request, EntityManagerInterface $entityManager){

        $sortie = new Sortie();

        $formSortie = $this->createForm(SortieType::class, $sortie);
        $formSortie->handleRequest($request);
        if($formSortie->isSubmitted() && $formSortie->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success',"Votre sortie à bien été ajoutée");

            //TODO rediriger vers le detail de l'annonce
            return $this->redirectToRoute("home_home");
        }

        return $this->render("sortie/creerSortie.html.twig",["formSortie" => $formSortie->createView()]);
    }


}