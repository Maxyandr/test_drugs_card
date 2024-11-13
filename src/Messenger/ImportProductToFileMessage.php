<?php

declare(strict_types=1);

namespace App\Messenger;

class ImportProductToFileMessage
{
    public function __construct(
        private string $filename,
        private int $productItemId
    ) {}

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getProductItemId(): int
    {
        return $this->productItemId;
    }


}
