<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\Entity\Product;
use App\Scraping\Model\ProductItem;

class ProductItemToProductFactory
{
    public function create(ProductItem $item): Product
    {
        return (new Product())->setProductUrl($item->getUrl())
            ->setName($item->getTitle())
            ->setPrice($item->getMainProductData()->getPrice())
            ->setImgUrl($item->getMainProductData()->getImages()[0]?->getSrc());
    }
}
