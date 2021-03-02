<?php


namespace App\Controller\lieu;



use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="lieu_", path="/lieu")
 */
class LieuController extends AbstractController{

    /**
     * @Route(name="creerlieu", path="/creerLieu", methods={"GET", "POST"})
     */

    public function lieuForm(Request $request, EntityManagerInterface $em){

        // Initialiser l'objet mappé au formulaire
        $lieu = new Lieu();

        // Création du formulaire
        $form = $this->createForm(LieuType::class, $lieu);

        // Récupération des données de la requête HTTP (Navigateur) au formulaire
        $form->handleRequest($request);

        // Vérification de la soumission du formulaire
        if($form->isSubmitted() && $form->isValid()) {

            // Insertion de l'objet en BDD
            $em->persist($lieu);
            // Validation de la transaction
            $em->flush();

            $this->addFlash('success','Le lieu a bien été ajouté');
            // Redirection sur la page "Gérer les lieux"
            return $this->redirectToRoute('lieu_lieu');
        }else{
            $this->addFlash('errors',"Le lieu n'a pas été ajouté");
        }

        // Appel à la vue pour afficher le formulaire
        return $this->render('lieu/lieu.html.twig', ['LieuType' => $form->createView()]);
    }


    /**
     * @Route(name="listeLieux", path="/lieu", methods={"GET"})
     */
    public function listeLieux(Request $request, EntityManagerInterface $entityManager){

        $lieux = $entityManager->getRepository(Lieu::class)->getAllLieu();

        return $this->render("lieu/lieu.html.twig",["lieux"=> $lieux]);
    }




}