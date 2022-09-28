<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Category;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Builder\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\JsonDeserializationStrictVisitor;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RecetteController extends AbstractController
{

    //A demander : 
    // - Enum Erreur ???
    // - Changer l'id de ma table Recette lors de la méthode Put
    // Où stocker mes images ? dans le serveur ? 

    /**
     * @Route("api/recette", name="app_recette", methods={"GET"})
     */
    public function index(RecetteRepository $recetteRepository, SerializerInterface $serializerInterface ): JsonResponse
    {
        $recette = $recetteRepository->findAll();
        
        $jsonRecette = $serializerInterface->serialize($recette, 'json', ["groups"=>"getRecette"], true);
        return new JsonResponse($jsonRecette, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("api/recette/{id}", name="RecetteById", methods={"GET"})
     */
    public function GetRecetteById(int $id,RecetteRepository $recetteRepository, SerializerInterface $serializerInterface) : JsonResponse
    {
        
        $recette = $recetteRepository->find($id);
            if($recette != null){
                $jsonRecette = $serializerInterface->serialize($recette, 'json');
                return new JsonResponse($jsonRecette, Response::HTTP_OK, ["groups"=>"getRecette"], true);
            }
            else{
                return new JsonResponse("La recette n'existe pas !",Response::HTTP_OK, [], true);
            }
    }

    /**
     * @Route("api/recette/{id}", name="deleteRecette", methods={"DELETE"})
     */
    public function DeleteRecette(EntityManagerInterface $em,int $id,RecetteRepository $recetteRepository, SerializerInterface $serializerInterface ) :JsonResponse
    {   
        $recette = $recetteRepository->find($id);
    
        $em->remove($recette);
        $em->flush();
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
   }

    /**
     * @Route("api/recette/{id}", name="updateRecette", methods={"PUT"})
     */
   public function updateRecette(Request $request,EntityManagerInterface $em,int $id,RecetteRepository $recetteRepository) :JsonResponse
    {   
        $recette = $recetteRepository->find($id);
        $data = $request->toArray();
        $category = $em->getRepository(Category::class)->find($data['categoryPlat']['id']);
        
        if($recette != null){
            
            $recette->setTitle($data['title']);
            $recette->setCategoryPlat($category);
           
            $em->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse("La recette n'existe pas", Response::HTTP_OK);
   }

   /**
    * @Route ("api/recette/user/{idUser}" ,name = "UserByID", methods={"GET"})
    */
    public function GetUserByID(int $idUser, RecetteRepository $recetteRepository, SerializerInterface $serializerInterface)  : JsonResponse
    {
        $recette = $recetteRepository->findUserByID($idUser);
        
        $recetteJson =  $serializerInterface->serialize($recette, 'json');

        return new JsonResponse($recetteJson, Response::HTTP_OK,["groups"=>"getRecette"], true);

    }


   /**
     * @Route("api/recette/new", name="newRecette", methods={"POST"})
     */
   public function addRecette(UserPasswordHasherInterface $userPasswordHasher, Request $request,EntityManagerInterface $em, SerializerInterface $serializerInterface) :JsonResponse
   {   
        $recette = new Recette();
        $category = new Category();
        $user = new User();
     
        $data = $request->toArray();
        $user->setId($data['user']['id']);
        $user->setEmail($data['user']['email']);
        $user->setPassword($data['user']['password']);
        $user->setUsername($data['user']['username']);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $data['user']['password']
            )
        );
        $category->setType("viande");
        $recette->setTitle($data['title']);
        $recette->setDescription($data['description']);
        $recette->setUser($user);
        $recette->setCategoryPlat($category);
        
        $em->persist($category);
        $em->persist($recette);
        $em->persist($user);
       
        $em->flush();

       $jsonRecette = $serializerInterface->serialize($recette, 'json', ["groups"=>"getRecette"]);
       return new JsonResponse($jsonRecette, Response::HTTP_CREATED, [], true);
   
  }
}
