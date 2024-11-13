<?php

declare(strict_types=1);

namespace App\Service\Provider;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class FileCsvProvider
{
    public function __construct(
        private Filesystem $filesystem,
        #[Autowire(param: 'app.directory.for.scraped.data')]
        private string $scrapedDirectory,
    ) {
        if (!$this->filesystem->exists($this->scrapedDirectory)) {
            $this->filesystem->mkdir($this->scrapedDirectory);
        }
    }

    public function createFileIfNotExists(string $filename, string $headers)
    {
        if (!$this->filesystem->exists($this->getFilePath($filename))) {
            $this->filesystem->touch($this->getFilePath($filename));
            $this->filesystem->dumpFile($this->getFilePath($filename), $headers . "\n");
        }
    }

    public function append(string $filename, string $content)
    {
        $this->filesystem->appendToFile($this->getFilePath($filename), $content . "\n");
    }

    private function getFilePath(string $filename): string
    {
        $subdir = $this->scrapedDirectory . DIRECTORY_SEPARATOR . 'productItems';
        return $subdir . DIRECTORY_SEPARATOR . $filename;
    }
}
