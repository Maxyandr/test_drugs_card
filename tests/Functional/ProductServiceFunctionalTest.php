<?php

declare(strict_types=1);

namespace App\Tests\Functional\Service;

use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class ProductServiceFunctionalTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private ProductService $productService;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->productService = $kernel->getContainer()->get(ProductService::class);
    }

    public function testGetAndSaveProductByUrl(): void
    {
        $url = 'http://example.com/product';

        // Call the method
        $this->productService->getAndSaveProductByUrl($url);

        // Fetch the product from the database
        $productRepository = $this->entityManager->getRepository(Product::class);
        $product = $productRepository->findOneBy(['url' => $url]);

        // Assert that the product was saved correctly
        $this->assertNotNull($product);
        $this->assertEquals($url, $product->getUrl());

        // Clean up
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}
