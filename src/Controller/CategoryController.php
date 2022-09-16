<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="app_category")
     */
    public function index(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();

        return $this->json([
            $categories, Response::HTTP_OK
        ]);
    }

    /**
     * @Route("/category/{id}", name="categoryById")
     */
    public function CategoryById(int $id, CategoryRepository $categoryRepository, SerializerInterface $serializerInterface ) : JsonResponse
    {
        $category = $categoryRepository->find($id);
        if(isset($category)){
            return $this->json([
                $category, Response::HTTP_OK
            ]);
        }else{
            return new JsonResponse("La category n'existe pas !",Response::HTTP_OK, [], true);           
        }
    }
}
