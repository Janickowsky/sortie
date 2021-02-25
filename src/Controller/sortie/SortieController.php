<?php


namespace App\Controller\sortie;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="sortie_", path="/sortie")
 */
class SortieController extends AbstractController{

    const ETAT_OUVERTURE = 'Ouverte';
    const ETAT_CREEE = 'Créée';

    /**
     * @Route(name="listeSorties", path="/sorties", methods={"GET"})
     */
    public function listeSorties(Request $request, EntityManagerInterface $entityManager){

        $sorties = $entityManager->getRepository(Sortie::class)->getAllSortie();

        return $this->render("sortie/sortie.html.twig",["sorties" => $sorties]);
    }

    /**
     * @Route(name="detailSortie", path="/detailsortie-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function detailSortie(Request $request, EntityManagerInterface $entityManager){

        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));
        return $this->render("sortie/sortieDetail.html.twig", [
            'sortie'=>$sortie
        ]);
    }
    /**
     * @Route(name="creerSortie", path="/creersortie", methods={"GET", "POST"})
     */
    public function creerSorties(Request $request, EntityManagerInterface $entityManager){

        $sortie = new Sortie();
        $sortie->setDateHeureDebut(new \Datetime());
        $sortie->setDateLimiteInscription(new \Datetime());

        $formSortie = $this->createForm(SortieType::class, $sortie);
        $formSortie->handleRequest($request);

        if($formSortie->isSubmitted() && $formSortie->isValid()) {

            //recuperation de l'utilisateur connecte
            $user = $this->getUser();

            //l'utilisateur connecte est l'organisateur de la sortie
            $sortie->setOrganisateur($user);

            //recupere le campus de l'utilissateur connecte
            $sortie->setCampus($user->getCampus());

            $etat = new Etat();
            if ($request->request->get('save')) {
                $etat = $entityManager->getRepository(Etat::class)->getEtatByLibelle(self::ETAT_CREEE);
            }elseif($request->request->get('publish')){
                $etat = $entityManager->getRepository(Etat::class)->getEtatByLibelle(self::ETAT_OUVERTURE);
            }

            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success',"Votre sortie à bien été ajoutée");

            return $this->redirectToRoute('sortie_detailSortie', ['id' => $sortie->getId()]);
        }

        return $this->render("sortie/creerSortie.html.twig",["formSortie" => $formSortie->createView()]);
    }

    /**
     * @Route(name="modifierSortie", path="/modifiersortie-{id}", requirements={"id":"\d+"}, methods={"GET", "POST"})
     */
    public function modifierSorties(Request $request, EntityManagerInterface $entityManager){
        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));

        if($sortie->getOrganisateur() == $this->getUser()) {

            $formSortie = $this->createForm(SortieType::class, $sortie);
            $formSortie->handleRequest($request);

            if ($formSortie->isSubmitted() && $formSortie->isValid()) {
                //recuperation de l'utilisateur connecte
                $user = $this->getUser();

                //l'utilisateur connecte est l'organisateur de la sortie
                $sortie->setOrganisateur($user);

                //recupere le campus de l'utilissateur connecte
                $sortie->setCampus($user->getCampus());

                $etat = new Etat();
                if ($request->request->get('save')) {
                    $etat = $entityManager->getRepository(Etat::class)->getEtatByLibelle(self::ETAT_CREEE);
                } elseif ($request->request->get('publish')) {
                    $etat = $entityManager->getRepository(Etat::class)->getEtatByLibelle(self::ETAT_OUVERTURE);
                }

                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('sortie_detailSortie', ['id' => $sortie->getId()]);
            }

            return $this->render("sortie/modifierSortie.html.twig", ["formSortie" => $formSortie->createView()]);
        }else{
            return $this->redirectToRoute('home_home');
        }
    }

    /**
     * @Route(name="supprimerSortie", path="/supprimersortie-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function supprimerSorties(Request $request, EntityManagerInterface $entityManager){
        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));
        if($sortie->getOrganisateur() == $this->getUser()) {
            $entityManager->remove($sortie);
            $entityManager->flush();

            $this->addFlash('success', "Votre sortie à bien été supprimée");
        }

        return  $this->redirectToRoute('home_home');
    }

}