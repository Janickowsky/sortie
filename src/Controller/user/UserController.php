<?php


namespace App\Controller\user;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route(name="user_", path="/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route(name="monProfil", path="/monProfil", methods={"GET", "POST"})
     */
    public function profilForm(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        if ($this->getUser()) {
            $user = $this->getUser();
        }


        // Création du formulaire
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($user->getPlainPassword())){
                $user->setPassword($user->encodePassword($encoder));
            }

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('user_monProfil');
        }

        // Appel à la vue pour afficher le formulaire
        return $this->render('user/monProfil.html.twig', ['UserType' => $form->createView()]);
    }


    /**
     * @Route(name="detailUser", path="/detailuser-{id}", requirements={"id": "\d+"}, methods={"GET"})
     */
    public function userDetails(Request $request, EntityManagerInterface $em)
    {
        $user = $em->getRepository(User::class)->getUserById($request->get('id'));

        return $this->render('user/userDetails.html.twig', ['user' => $user]);
    }
}