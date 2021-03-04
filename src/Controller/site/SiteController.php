<?php


namespace App\Controller\site;



use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SiteSearchType;
use App\Form\SiteType;
use App\Form\SortieSearchType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(name="site_", path="/site")
 */
class SiteController extends AbstractController{


    /**
     * @Route(name="listeSite", path="/listesite", methods={"GET","POST"})
     */
    public function listeSite(Request $request, EntityManagerInterface $entityManager){
        $formSearch = $this->createForm(SiteSearchType::class);
        $formSearch->handleRequest($request);

        if($formSearch->isSubmitted() && $formSearch->isValid()){
            $datas = $formSearch->getData();
            $sites= $entityManager->getRepository(Site::class)->getAllSite($datas);
        }else{
            $sites = $entityManager->getRepository(Site::class)->getAllSite();
        }

        return $this->render("site/site.html.twig",["sites" => $sites, "formSearch" =>$formSearch->createView()]);
    }

    /**
     * @Route(name="ajouterSite", path="/ajoutersite", methods={"GET","POST"})
     */
    public function ajouterSite(Request $request, EntityManagerInterface $entityManager){
        $site = new Site();
        $formSite = $this->createForm(SiteType::class, $site);
        $formSite->handleRequest($request);

        if($formSite->isSubmitted() && $formSite->isValid()){

            $entityManager->persist($site);
            $entityManager->flush();
            $this->addFlash('success',"Votre site " .$site->getNom(). " a bien été ajouté");
            return $this->redirectToRoute('site_listeSite');
        }
        return $this->render("site/creerSite.html.twig", ["formSite" => $formSite->createView()]);
    }

    /**
     * @Route (name="supprimerSite", path="/supprimersite-{id}", requirements={"id":"\d+"}, methods={"GET"})
     */
    public function supprimerSite(Request $request, EntityManagerInterface $entityManager){
        $site = $entityManager->getRepository(Site::class)->getSiteById($request->get('id'));
        if(count($site->getSorties()) > 0){
            $this->addFlash('errors', "Le site " .$site->getNom(). " ne peut pas être supprimé");
        }else{
            $entityManager->remove($site);
            $entityManager->flush();

            $this->addFlash('success', "Le site " .$site->getNom(). " a bien été supprimé");
        }

        return  $this->redirectToRoute('site_listeSite');
    }

    /**
     * @Route(name="modifierSite", path="/modifiersite-{id}", requirements={"id":"\d+"}, methods={"GET", "POST"})
     */
    public function modifierVille(Request $request, EntityManagerInterface $entityManager){
        $site = $entityManager->getRepository(Site::class)->getSiteById($request->get('id'));

        $formSite = $this->createForm(SiteType::class, $site);
        $formSite->handleRequest($request);

        if($formSite->isSubmitted() && $formSite->isValid()) {

            $entityManager->persist($site);
            $entityManager->flush();

            $this->addFlash('success', "Le site " . $site->getNom() . " a bien été modifié");

            return $this->redirectToRoute('site_listeSite', ['id' => $site->getId()]);
        }
        return $this->render("site/modifierSite.html.twig", ["formSite" => $formSite->createView()]);
    }

}