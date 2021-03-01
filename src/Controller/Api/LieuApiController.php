<?php

namespace App\Controller\Api;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
}
