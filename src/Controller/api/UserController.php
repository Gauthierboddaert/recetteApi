<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    
    /**
    * @Route ("api/account" ,name = "favoritesByUser", methods={"GET"})
    */
    public function getFavoritesUser(RecetteRepository $recetteRepository, SerializerInterface $serializerInterface)  : JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $tab = [
            "user" => $user,
            "recetteUser" => $recetteRepository->findRecetteByUser($user->getId()),
            "favorite" => $recetteRepository->findFavoriteByUser($user->getId())
        ];

        $recetteJson =  $serializerInterface->serialize($tab, 'json',["groups"=>"getRecette"]);
        return new JsonResponse($recetteJson, Response::HTTP_OK,["groups"=>"getRecette"], true);

    }
}
