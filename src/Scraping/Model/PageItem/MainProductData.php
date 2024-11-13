<?php

declare(strict_types=1);

namespace App\Scraping\Model\PageItem;

use Symfony\Component\Serializer\Annotation\Groups;

class MainProductData
{
    /**
     * @var Image[]
     */
    private array $images;

    private string $tour360;

    /**
     * @var MainAttribute[]
     */
    private array $mainAttributes;

    #[Groups(['base'])]
    private string $price;

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getMainAttributes(): array
    {
        return $this->mainAttributes;
    }

    public function setMainAttributes(array $mainAttributes): void
    {
        $this->mainAttributes = $mainAttributes;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    public function getTour360(): string
    {
        return $this->tour360;
    }

    public function setTour360(string $tour360): void
    {
        $this->tour360 = $tour360;
    }
}
