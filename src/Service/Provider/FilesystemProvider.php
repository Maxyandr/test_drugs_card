<?php

declare(strict_types=1);

namespace App\Service\Provider;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemProvider
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

    public function save(string $filename, string $content): void
    {
        $subdir = $this->scrapedDirectory . DIRECTORY_SEPARATOR . 'productItems';

        if (!$this->filesystem->exists($subdir)) {
            $this->filesystem->mkdir($subdir);
        }
        $this->filesystem->dumpFile($subdir . DIRECTORY_SEPARATOR . $filename, $content);

    }
}
