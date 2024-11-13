<?php

declare(strict_types=1);

namespace App\Scraping\Processor;

use App\Scraping\Model\Languages;
use Symfony\Component\DomCrawler\Crawler;

class LanguageUrlsProcessor
{
    public function processor(Crawler $crawler): array
    {
        return $crawler->filter('#languages div a')->each(function (Crawler $node, $i) use ($crawler): Languages {
            return new Languages(
                $crawler->getUri(),
                $node->attr('href'),
                $node->text(),
                str_replace('-lang dropdown-item', '', $node->attr('class')),
            );
        });
    }
}
