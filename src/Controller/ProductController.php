<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{


    public function __construct(private NormalizerInterface $normalizer)
    {
    }

    #[Route('/product/{id}', name: 'app_product', methods: ['GET'])]
    public function view(Product $product): JsonResponse
    {
        return $this->json($this->normalizer->normalize($product));
    }

    #[Route('/product', name: 'app_product_collection', methods: ['GET'])]
    public function collection(ProductRepository $productRepository): JsonResponse
    {
        return $this->json($this->normalizer->normalize($productRepository->findAll()));
    }

    #[Route('/product/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function delete(ProductRepository $productRepository, Product $product): JsonResponse
    {
        $productRepository->delete($product);
        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
