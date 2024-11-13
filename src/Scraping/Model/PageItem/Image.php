<?php

declare(strict_types=1);

namespace App\Scraping\Model\PageItem;

use Symfony\Component\Serializer\Annotation\Ignore;

class Image
{
    public function __construct(
        #[Ignore]
        private string $alt,
        private string $src,
        #[Ignore]
        private ?string $content = null,
        #[Ignore]
        private ?string $type = null,
    ) {
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }
}
