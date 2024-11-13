<?php

declare(strict_types=1);

namespace App\Scraping\Model\PageItem;

readonly class Description
{

    /**
     * @param string $description
     * @param Feature[] $features
     */
    public function __construct(
        private string $description,
        private array  $features,
    ) {
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getFeatures(): array
    {
        return $this->features;
    }
}
