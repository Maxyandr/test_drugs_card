<?php

declare(strict_types=1);

namespace App\Scraping\Model\PageItem;

readonly class VideoReview
{

    public function __construct(
        private string $url,
    ) {
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
