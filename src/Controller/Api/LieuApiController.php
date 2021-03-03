<?php

namespace App\Controller\Api;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LieuApiController extends AbstractController
{
    /**
     * @Route(path="/api/lieu/api/recuplieuByIdVille-{id}", requirements={"id":"\d+"}, name="api_lieuByIdVille_api", methods={"GET"})
     */
    public function lieuxByIdVille(LieuRepository $lr, SerializerInterface $serializer, Request $request) :Response{
        $lieu = $lr->getLieuByIdVille($request->get('id'));
        $json = $serializer->serialize($lieu, 'json', ['groups'=>['api_lieu']]);

        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route(path="/api/lieu/api/recuplieuById-{id}", requirements={"id":"\d+"}, name="api_lieuById_api", methods={"GET"})
     */
    public function lieuxById(LieuRepository $lr, SerializerInterface $serializer, Request $request) :Response{
        $lieu = $lr->getLieuById($request->get('id'));
        $json = $serializer->serialize($lieu, 'json', ['groups'=>['api_lieu']]);

        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route(path="/api/lieu/api/lieu", name="api_lieu_api", methods={"POST"})
     */
    public function addLieu(VilleRepository $vr, SerializerInterface $serializer, Request $request,EntityManagerInterface $manager,ValidatorInterface $validator){

        if($request->isXmlHttpRequest()) {
            $content = $request->getContent();
            $lieu = $serializer->deserialize($content, Lieu::class, 'json');


            //$lieu->setVille($ville);
            $lieu->setLongitude(-81.506485);
            $lieu->setLatitude(35.666156);

            $errors = $validator->validate($lieu);

            $return = ['result' => true, 'errors' => []];

            if (count($errors) === 0) {
                $manager->persist($lieu);
                $manager->flush();
            } else {
                $return['result'] = false;
                foreach ($errors as $e) {
                    $return['errors'][] = $e->getMessage();
                }
            }
        }
        return new JsonResponse($return, 200);
    }

    /**
     * @Route(path="/api/lieu/api/villes", name="api_ville_api", methods={"GET"})
     */
    public function getVilles(VilleRepository $vr, SerializerInterface $serializer) :Response{
        $villes = $vr->getAllVille();
        $json = $serializer->serialize($villes, 'json', ['groups'=>['api_ville']]);

        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route(path="/api/lieu/api/ville-{id}", requirements={"id":"\d+"} , name="api_villeById_api", methods={"GET"})
     */
    public function getVillesById(VilleRepository $vr, SerializerInterface $serializer,Request $request) :Response{
        $ville = $vr->getVilleById($request->get('id'));

        $json = $serializer->serialize($ville, 'json', ['groups'=>['api_ville']]);

        return new JsonResponse($json, 200, [], true);

    }
}
