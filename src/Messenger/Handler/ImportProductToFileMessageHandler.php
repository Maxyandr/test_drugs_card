<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\ImportProductToFileMessage;
use App\Repository\ProductRepository;
use App\Service\Provider\FileCsvProvider;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsMessageHandler]
class ImportProductToFileMessageHandler
{
    public function __construct(
        private FileCsvProvider   $fileCsvProvider,
        private ProductRepository $productRepository,
        private NormalizerInterface $normalizer,
    ) {}

    public function __invoke(ImportProductToFileMessage $message)
    {
        $productEntity = $this->productRepository->find($message->getProductItemId());
        $product = $this->normalizer->normalize($productEntity);
        $this->fileCsvProvider->createFileIfNotExists(
            $message->getFilename(),
            implode(',', array_keys($product))
        );

        $this->fileCsvProvider->append(
            $message->getFilename(),
            implode(',', array_values($product))
        );

    }
}
