<?php

declare(strict_types=1);

namespace App\Scraping\Model;

use App\Scraping\Model\PageItem\Description;
use App\Scraping\Model\PageItem\MainProductData;
use App\Scraping\Model\PageItem\Specification;
use App\Scraping\Model\PageItem\Support;
use App\Scraping\Model\PageItem\VideoReview;
use Symfony\Component\Serializer\Annotation\Groups;

class ProductItem
{

    /**
     * @var Specification[]
     */
    private ?array $specifications = null;

    /**
     * @var Support[]
     */
    private ?array $support = null;

    /**
     * @var VideoReview[]
     */
    private ?array $videoReviews = null;
    private ?Description $description = null;
    private ?MainProductData $mainProductData = null;
    private string $productType;

    #[Groups(['base'])]
    private string $title;

    public function __construct(
        #[Groups(['base'])]
        private string $url,
    ) {
    }

    public function getProductType(): string
    {
        return $this->productType;
    }

    public function setProductType(string $productType): void
    {
        $this->productType = $productType;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?Description
    {
        return $this->description;
    }

    public function setDescription(?Description $description): void
    {
        $this->description = $description;
    }

    public function getSpecifications(): ?array
    {
        return $this->specifications;
    }

    public function setSpecifications(?array $specifications): void
    {
        $this->specifications = $specifications;
    }

    public function getSupport(): ?array
    {
        return $this->support;
    }

    public function setSupport(?array $support): void
    {
        $this->support = $support;
    }

    public function getVideoReviews(): ?array
    {
        return $this->videoReviews;
    }

    public function setVideoReviews(?array $videoReviews): void
    {
        $this->videoReviews = $videoReviews;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMainProductData(): ?MainProductData
    {
        return $this->mainProductData;
    }

    public function setMainProductData(?MainProductData $mainProductData): void
    {
        $this->mainProductData = $mainProductData;
    }
}
