<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(length: 255, nullable: false)]
    private string $price;

    #[ORM\Column(length: 255, nullable: false)]
    private string $imgUrl;

    #[ORM\Column(length: 255, nullable: false)]
    private string $productUrl;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): Product
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    public function getProductUrl(): string
    {
        return $this->productUrl;
    }

    public function setProductUrl(string $productUrl): Product
    {
        $this->productUrl = $productUrl;
        return $this;
    }
}
