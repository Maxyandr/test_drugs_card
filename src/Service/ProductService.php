<?php

declare(strict_types=1);

namespace App\Service;

use App\Messenger\ImportProductToFileMessage;
use App\Scraping\ScrapingProvider;
use App\Service\Factory\ProductItemToProductFactory;
use App\Service\Provider\FilesystemProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductService
{
    const BATCH_ITEM_COUNT = 10;

    public function __construct(
        private ScrapingProvider            $scrapingProcessor,
        private FilesystemProvider          $filesystemProvider,
        private NormalizerInterface         $normalizer,
        private SerializerInterface         $serializer,
        private ProductItemToProductFactory $toProductFactory,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
    ) {
    }

    public function getAndSaveProductByUrl(string $url)
    {
        $productItem = $this->scrapingProcessor->processProductUrl($url);

        // Saving to filesystem example
        $normalized = $this->normalizer->normalize($productItem, null, ['groups' => 'base']);
        $serializedProducts = $this->serializer->serialize($normalized, 'csv');
        $this->filesystemProvider->save('product.' . md5($url) . '.csv', $serializedProducts);

        $productEntity = $this->toProductFactory->create($productItem);
        $this->entityManager->persist($productEntity);
        $this->entityManager->flush();
    }

    public function getAndSaveProductData(string $lang, array $except)
    {
        $products = $this->scrapingProcessor->scrapeProductData($lang, $except);

        // Saving to filesystem example SYNC saving like direct dump
        $normalized = $this->normalizer->normalize($products, null, ['groups' => 'base']);
        $serializedProducts = $this->serializer->serialize($normalized, 'csv');
        $this->filesystemProvider->save('sync_product.list.' . $lang . '.csv', $serializedProducts);

        foreach ($products as $productItem) {
            $productEntity = $this->toProductFactory->create($productItem);
            $this->entityManager->persist($productEntity);
            $this->entityManager->flush();

            // Async saving to filesystem (or cloud)
            $this->messageBus->dispatch(
                new ImportProductToFileMessage('async_product.list.' . $lang . '.csv', $productEntity->getId())
            );
        }
    }
}
