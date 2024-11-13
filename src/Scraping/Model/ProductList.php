<?php

declare(strict_types=1);

namespace App\Scraping\Model;

use Doctrine\Common\Collections\ArrayCollection;

class ProductList
{
    /** @var ArrayCollection|ProductItem[]  */
    private ArrayCollection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|ProductItem[]
     */
    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection|ProductItem[] $products
     */
    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
    }

}
