<?php

declare(strict_types=1);

namespace App\Scraping\Model;

class Languages
{
    public function __construct(
        private readonly string $domain,
        private readonly string $url,
        private readonly string $languageText,
        private readonly string $languageCode,
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLanguageText(): string
    {
        return $this->languageText;
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
