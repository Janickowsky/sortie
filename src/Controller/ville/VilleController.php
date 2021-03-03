<?php


namespace App\Controller\ville;



use App\Entity\Ville;
use App\Form\CreateVilleType;
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
     * @Route(name="creerVille", path="/creerville", methods={"GET", "POST"})
     */
    public function creerVille(Request $request, EntityManagerInterface $entityManager){
        $ville = new Ville();
        $formCreateVille = $this->createForm(CreateVilleType::class, $ville);
        $formCreateVille->handleRequest($request);

        if($formCreateVille->isSubmitted() && $formCreateVille->isValid()){

            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash('success',"Votre ville" .$ville->getNom(). " a bien été ajoutée");
            return $this->redirectToRoute('ville_listeVille');
        }
        return $this->render("ville/creerVille.html.twig", ["formCreateVille" => $formCreateVille->createView()]);
    }

    /**
     * @Route (name="supprimerVille", path="/supprimerville-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function supprimerVille(Request $request, EntityManagerInterface $entityManager){
        $ville = $entityManager->getRepository(Ville::class)->getVilleById($request->get('id'));
        $entityManager->remove($ville);
        $entityManager->flush();

        $this->addFlash('success', "La sortie " .$ville->getNom(). " a bien été supprimée");

        return  $this->redirectToRoute('ville_listeVille');
    }

    public function modifierVille(Request $request, EntityManagerInterface $entityManager){
        $ville = $entityManager->getRepository(Ville::class)->getVilleById($request->get('id'));

    }
}