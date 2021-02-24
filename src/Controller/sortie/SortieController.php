<?php


namespace App\Controller\sortie;


use App\Entity\Sortie;
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
     * @Route(name="detailSortie", path="/detailSortie-{id}", methods={"GET"})
     */
    public function sortieDetail(Request $request, EntityManagerInterface $em){
        $id = $request->get('id');
        $sortie= $em->getRepository('App:Sortie')->getIdeaByIdQB($id);

        return $this->render("sortie/sortieDetail.html.twig", ['sortie' => $sortie]);
    }
}