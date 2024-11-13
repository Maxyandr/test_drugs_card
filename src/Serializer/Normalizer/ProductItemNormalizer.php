<?php

namespace App\Serializer\Normalizer;

use App\Scraping\Model\PageItem\Image;
use App\Scraping\Model\ProductItem;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductItemNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }


    public function normalize($object, ?string $format = null, array $context = []): array
    {
        /** @var ProductItem $object */
        $data = $this->normalizer->normalize($object, $format, $context);
        $images = $object->getMainProductData()->getImages();
        $data['img_url'] = $images[0]?->getSrc();
        $data['price'] = $object->getMainProductData()->getPrice();
        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ProductItem;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ProductItem::class => true];
    }
}
