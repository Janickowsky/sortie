<?php


namespace App\Controller\sortie;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieSearchType;
use App\Form\SortieAnnulerType;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;


/**
 * @Route(name="sortie_", path="/sortie")
 */
class SortieController extends AbstractController{

    const ETAT_OUVERTURE = 'Ouverte';
    const ETAT_CREEE = 'Créée';
    const ETAT_ENCOURS = 'Activité en cours';
    const ETAT_ANNULEE = 'Annulée';

    /**
     * @Route(name="listeSorties", path="/sorties", methods={"GET","POST"})
     */
    public function listeSorties(Request $request, EntityManagerInterface $entityManager){

        $formSearch = $this->createForm(SortieSearchType::class);

        $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() && $formSearch->isValid()){
            $site = $formSearch->get('site')->getData();
            $nomSortie = $formSearch->get('nomSortie')->getData();
            $dateDepart = $formSearch->get('dateDepart')->getData();
            $dateFin =  $formSearch->get('dateFin')->getData();
            $orgaTri = $formSearch->get('orgaTri')->getData();
            $inscritTri = $formSearch->get('inscritTri')->getData();
            $nonInscritTri = $formSearch->get('nonInscritTri')->getData();
            $passeTri = $formSearch->get('passeTri')->getData();

            $sorties= $entityManager->getRepository(Sortie::class)->getSortieSearch(
                $this->getUser(),
                $site ?? null,
                $nomSortie ?? null,
                $dateDepart ?? null,
                $dateFin ?? null,
                $orgaTri ?? false,
                $inscritTri ?? false,
                $nonInscritTri ?? false,
                $passeTri ?? false
            );
        }else{
            $sorties = $entityManager->getRepository(Sortie::class)->getAllSortie($this->getUser());
        }


        return $this->render("sortie/sortie.html.twig",["sorties" => $sorties,"formSearch" =>$formSearch->createView()]);
    }

    /**
     * @Route(name="detailSortie", path="/detailsortie-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function detailSortie(Request $request, EntityManagerInterface $entityManager){

        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));
        if($sortie->getEtat()->getLibelle() == self::ETAT_ANNULEE){
            $sortie->setInfosSortie($sortie->getInfosSortie() . "\nMotif d'annualation : " .$sortie->getMotif());
        }


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

            $this->addFlash('success',"Votre sortie n°" .$sortie->getNom(). " a bien été ajoutée");

            return $this->redirectToRoute('home_home');
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
                    $this->addFlash('success',"La sortie " .$sortie->getNom(). " a bien été modifiée");
                } elseif ($request->request->get('publish')) {
                    $etat = $entityManager->getRepository(Etat::class)->getEtatByLibelle(self::ETAT_OUVERTURE);
                    $this->addFlash('success',"La sortie " .$sortie->getNom(). " a bien été publiée");
                }

                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('sortie_listeSorties', ['id' => $sortie->getId()]);
            }

            return $this->render("sortie/modifierSortie.html.twig", ["formSortie" => $formSortie->createView()]);

        }else{
            $this->addFlash('errors',"Vous n'êtes pas l'organisateur de cette sortie");
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

            $this->addFlash('success', "La sortie " .$sortie->getNom(). " a bien été supprimée");
        }else{
            $this->addFlash('errors', "La sortie ".$sortie->getNom()." n'a été supprimée");
            return  $this->redirectToRoute('home_home');
        }
    }

    /**
     * @Route(name="sinscrire", path="/sinscrire-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function sinscrire(Request $request, EntityManagerInterface $entityManager){
        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));

        if($sortie->getEtat()->getLibelle() == self::ETAT_OUVERTURE
            && count($sortie->getParticipants()) < $sortie->getNbInscriptionMax()
            && $sortie->getDateLimiteInscription() > new \DateTime('now'))
        {
            $sortie->addParticipant($this->getUser());
            $this->getUser()->addSorty($sortie);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Vous avez été bien inscrit à la sortie '.$sortie->getNom());
        }else{
            $this->addFlash('errors','Vous ne pouvez pas vous inscrire à la sortie '.$sortie->getNom());
        }
        return  $this->redirectToRoute('home_home');
    }

    /**
     * @Route(name="sedesister", path="/sedesister-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function sedesister(Request $request, EntityManagerInterface $entityManager){
        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));

        if($sortie->getEtat()->getLibelle() == self::ETAT_OUVERTURE && in_array($this->getUser(),$sortie->getParticipants()->toArray())){

            $sortie->removeParticipant($this->getUser());
            $this->getUser()->removeSorty($sortie);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success','Vous avez été bien désinscrit de la sortie '.$sortie->getNom());
        }else{
            $this->addFlash('errors','Vous ne pouvez pas vous désinscrire de la sortie '.$sortie->getNom());
        }
        return  $this->redirectToRoute('home_home');
    }

    /**
     * @Route(name="annulerSortie", path="/annulersortie-{id}", requirements={"id":"\d+"}, methods={"GET", "POST"})
     */
    public function annulerSortie(Request $request, EntityManagerInterface $entityManager){
        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));

        if($sortie->getOrganisateur() == $this->getUser()) {
            $formAnnuler = $this->createForm(SortieAnnulerType::class);
            $formAnnuler->handleRequest($request);
                if($formAnnuler->isSubmitted() && $formAnnuler->isValid()) {
                    if (!empty(trim($formAnnuler->get('motif')->getData()))) {
                        $sortie->setMotif($formAnnuler->get('motif')->getData());
                        $etat = $entityManager->getRepository(Etat::class)->getEtatByLibelle(self::ETAT_ANNULEE);
                        $sortie->setEtat($etat);
                        $entityManager->persist($sortie);
                        $entityManager->flush();

                        $this->addFlash('success', "La sortie " . $sortie->getNom() . " a bien été annulée");

                        return $this->redirectToRoute('home_home');
                    }else{
                        $this->addFlash('errors','Veuillez saisir un motif');
                    }
                }
                return $this->render("sortie/annulerSortie.html.twig", [
                    'sortie'=>$sortie,
                    'formAnnuler'=>$formAnnuler->createView()
                ]);
        }else{
            $this->addFlash('errors',"Vous n'êtes pas l'organisateur de cette sortie");
            return $this->redirectToRoute('home_home');
        }
    }
    /**
     * @Route(name="publierSortie", path="/publiersortie-{id}", requirements={"id":"\d+"}, methods={"GET", "POST"})
     */
    public function publierSortie(Request $request, EntityManagerInterface $entityManager){
        $sortie = $entityManager->getRepository(Sortie::class)->getSortieById($request->get('id'));
        if($this->getUser() == $sortie->getOrganisateur() && $sortie->getEtat()->getLibelle() == self::ETAT_CREEE){
            $etat = $entityManager->getRepository(Etat::class)->getEtatByLibelle(self::ETAT_OUVERTURE);
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success',"La sortie " .$sortie->getNom(). " a bien été publiée");
        } else {
            $this->addFlash('errors',"Impossible de publier l'annonce : " .$sortie->getNom());
        }

        return $this->redirectToRoute('home_home');
    }
}