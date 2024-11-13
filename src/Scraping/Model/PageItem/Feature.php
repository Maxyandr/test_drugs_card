<?php

declare(strict_types=1);

namespace App\Scraping\Model\PageItem;

readonly class Feature
{
    public function __construct(
        private string $title,
        private string $description,
        private string $systemTitle,
        private Image  $image,
    ) {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    public function getSystemTitle(): ?string
    {
        return $this->systemTitle;
    }
}
