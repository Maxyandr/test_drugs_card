<?php

declare(strict_types=1);

namespace App\Scraping\Model\PageItem;

use Symfony\Component\Serializer\Annotation\Ignore;

class Support
{

    public function __construct(
        private readonly string $url,
        private readonly string $title,
        private readonly Image  $image,
        #[Ignore]
        private ?string         $content = null,
        #[Ignore]
        private ?string         $type = null,
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
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
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
    public function getUrl(): string
    {
        return $this->url;
    }

}
