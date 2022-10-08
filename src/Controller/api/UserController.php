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
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    private RecetteRepository $recetteRepository;
    private UserRepository $userRepository;
    private SerializerInterface $serializerInterface;

    public function __construct(UserRepository $userRepository ,RecetteRepository $recetteRepository, SerializerInterface $serializerInterface)
    {
        $this->recetteRepository = $recetteRepository;
        $this->serializerInterface = $serializerInterface;    
        $this->userRepository = $userRepository;
    }
    
    /**
    * @Route ("api/account" ,name = "favoritesByUser", methods={"GET"})
    */
    public function getFavoritesUser()  : JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $tab = [
            "user" => $user,
            "recetteUser" => $this->recetteRepository->findRecetteByUser($user->getId()),
            "favorite" => $this->recetteRepository->findFavoriteByUser($user->getId())
        ];

        $recetteJson =  $this->serializerInterface->serialize($tab, 'json',["groups"=>"getRecette"]);
        return new JsonResponse($recetteJson, Response::HTTP_OK,["groups"=>"getRecette"], true);

    }

     /**
    * @Route ("api/loginUser" ,name = "favoritesByUser", methods={"get"})
    */
    public function login(Request $request)  : JsonResponse
    {
        $data = $request->toArray();
        $user = $this->userRepository->findUserByEmail($data['username']);
        return new JsonResponse($this->serializerInterface->serialize($user, 'json', ["groups" => "getUser"]), Response::HTTP_OK, [], true);
    }
}
