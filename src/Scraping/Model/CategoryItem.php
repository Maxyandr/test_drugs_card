<?php

declare(strict_types=1);

namespace App\Scraping\Model;

readonly class CategoryItem
{

    public function __construct(
        private string $siteLang,
        private string $subCategoryImg,
        private string $subCategoryName,
        private string $subCategoryUrl,
        private string $categoryName,
        private string $categoryId
    ) {
    }

    /**
     * @return string
     */
    public function getSubCategoryUrl(): string
    {
        return $this->subCategoryUrl;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @return string
     */
    public function getSubCategoryName(): string
    {
        return $this->subCategoryName;
    }

    /**
     * @return string
     */
    public function getSubCategoryImg(): string
    {
        return $this->subCategoryImg;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    public function getSiteLang(): string
    {
        return $this->siteLang;
    }
}
