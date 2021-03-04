<?php


namespace App\Controller\ville;



use App\Entity\Site;
use App\Entity\Ville;
use App\Form\SiteSearchType;
use App\Form\VilleSearchType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="ville_", path="/ville")
 */
class VilleController extends AbstractController{

    /**
     * @Route(name="listeVille", path="/ville", methods={"GET","POST"})
     */
    public function listeVille(Request $request, EntityManagerInterface $entityManager){
        $formSearch = $this->createForm(VilleSearchType::class);
        $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() && $formSearch->isValid()){
            $datas = $formSearch->getData();
            $villes= $entityManager->getRepository(Ville::class)->getAllVille($datas);
        }else{
            $villes = $entityManager->getRepository(Ville::class)->getAllVille();
        }

        return $this->render("ville/ville.html.twig",["villes" => $villes, "formSearch" =>$formSearch->createView()]);
    }

    /**
     * @Route(name="ajouterVille", path="/ajouterville", methods={"GET", "POST"})
     */
    public function ajouterVille(Request $request, EntityManagerInterface $entityManager){
        $ville = new Ville();
        $formVille = $this->createForm(VilleType::class, $ville);
        $formVille->handleRequest($request);

        if($formVille->isSubmitted() && $formVille->isValid()){

            $entityManager->persist($ville);
            $entityManager->flush();
            $this->addFlash('success',"Votre ville " .$ville->getNom(). " a bien été ajoutée");
            return $this->redirectToRoute('ville_listeVille');
        }
        return $this->render("ville/creerVille.html.twig", ["formVille" => $formVille->createView()]);
    }

    /**
     * @Route (name="supprimerVille", path="/supprimerville-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function supprimerVille(Request $request, EntityManagerInterface $entityManager){
        $ville = $entityManager->getRepository(Ville::class)->getVilleById($request->get('id'));
        if(count($ville->getLieux()) > 0){
            $this->addFlash('errors', "La sortie " .$ville->getNom(). " ne peut pas être supprimée");
        }else{
            $entityManager->remove($ville);
            $entityManager->flush();
            $this->addFlash('success', "La sortie " .$ville->getNom(). " a bien été supprimée");
        }




        return  $this->redirectToRoute('ville_listeVille');
    }

    /**
     * @Route(name="modifierVille", path="/modifierville-{id}", requirements={"id":"\d+"}, methods={"GET", "POST"})
     */
    public function modifierVille(Request $request, EntityManagerInterface $entityManager){
        $ville = $entityManager->getRepository(Ville::class)->getVilleById($request->get('id'));

        $formVille = $this->createForm(VilleType::class, $ville);
        $formVille->handleRequest($request);

        if($formVille->isSubmitted() && $formVille->isValid()) {

            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', "La ville " . $ville->getNom() . " a bien été modifiée");

            return $this->redirectToRoute('ville_listeVille', ['id' => $ville->getId()]);
        }
        return $this->render("ville/modifierVille.html.twig", ["formVille" => $formVille->createView()]);
    }
}