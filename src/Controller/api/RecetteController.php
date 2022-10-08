<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Recette;
use App\Entity\Category;
use App\Enum\EnumCategory;
use App\Repository\CategoryRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class RecetteController extends AbstractController
{


    private SerializerInterface $serializerInterface;
    private RecetteRepository $recetteRepository;
    private EntityManagerInterface $em;
    private Security $security;
    private UserRepository $userRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        SerializerInterface $serializerInterface,
        RecetteRepository $recetteRepository,
        EntityManagerInterface $em,
        Security $security,
        UserRepository $userRepository,
        CategoryRepository $categoryRepository
        
    ) {
        $this->serializerInterface = $serializerInterface;
        $this->recetteRepository = $recetteRepository;
        $this->em = $em;
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("api/recette", name="app_recette", methods={"GET"})
     * 
     */
    public function index(): JsonResponse
    {
        $recette = $this->recetteRepository->findAll();
        $jsonRecette = $this->serializerInterface->serialize($recette, 'json', ["groups" => "getRecette"], true);
        return new JsonResponse($jsonRecette, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("api/recette/{id}", name="RecetteById", methods={"GET"})
     */
    public function GetRecetteById(int $id): JsonResponse
    {

        $recette = $this->recetteRepository->find($id);
        if ($recette != null) {
            $jsonRecette = $this->serializerInterface->serialize($recette, 'json', ["groups" => "getRecette"]);
            return new JsonResponse($jsonRecette, Response::HTTP_OK, ["groups" => "getRecette"], true);
        } else {
            return new JsonResponse("La recette n'existe pas !", Response::HTTP_OK, [], true);
        }
    }

    /**
     * @Route("api/recette/{id}", name="deleteRecette", methods={"DELETE"})
     */
    public function DeleteRecette(int $id): JsonResponse
    {
        $recette = $this->recetteRepository->find($id);

        $this->em->remove($recette);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("api/recette/{id}", name="updateRecette", methods={"PUT"})
     */
    public function updateRecette(int $id, Request $request): JsonResponse
    {
        $recette = $this->recetteRepository->find($id);
        $data = $request->toArray();
        $category = $this->em->getRepository(Category::class)->find($data['categoryPlat']['id']);
        // $image = $this->em->getRepository(Image::class)->find($data['Images'][0]);    

        if ($recette != null) {
            $recette->setTitle($data['title']);
            $recette->setTitle($data['description']);
            $recette->setCategoryPlat($category);


            $this->em->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse("La recette n'existe pas", Response::HTTP_OK);
    }

    /**
     * @Route ("api/recette/user/{idUser}" ,name = "UserByID", methods={"GET"})
     */
    public function GetUserByID(int $idUser): JsonResponse
    {
        $recette = $this->recetteRepository->findUserByID($idUser);

        $recetteJson =  $this->serializerInterface->serialize($recette, 'json');

        return new JsonResponse($recetteJson, Response::HTTP_OK, ["groups" => "getRecette"], true);
    }


    /**
     * @Route("api/recette/new", name="newRecette", methods={"POST"})
     */
    public function addRecette(Request $request, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {
        $recette = new Recette();
        $data = $request->toArray();
        $category = $this->categoryRepository->findCategoryExist($data['Category']['type']);
        $user = $this->userRepository->findUserByEmail($data['email']);

        foreach ($data['images'] as $img) {
            $image = new Image();
            $image->setPath($img['path']);
            $recette->addImage($image);
            $this->em->persist($image);
        }

        
        $recette->setTitle($data['title']);
        $recette->setDescription($data['description']);
        $recette->setCategoryPlat($category[0]);
        $recette->setUser($user[0]);
        // $this->em->persist($category);
        $this->em->persist($recette);


        $this->em->flush();

        $jsonRecette = $this->serializerInterface->serialize($recette, 'json', ["groups" => "getRecette"]);
        return new JsonResponse($jsonRecette, Response::HTTP_CREATED, [], true);
    }
}

