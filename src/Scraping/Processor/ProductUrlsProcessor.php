<?php

declare(strict_types=1);

namespace App\Scraping\Processor;

use Symfony\Component\DomCrawler\Crawler;

class ProductUrlsProcessor
{
    private const NOT_PAGE_DATA_ENDPOINTS = [
        'dps-advantages',
        'dps-guarantee',
        'balcony-energy-solar-systems',
    ];

    public function process(Crawler $crawler): array
    {
        foreach (self::NOT_PAGE_DATA_ENDPOINTS as $str) {
            if (str_contains($crawler->getUri(), $str)) {
                return [];
            }
        }

        return $crawler->filter('div.results-list div.result-item a.thumb-wrapper')
            ->each(function (Crawler $node, $i) : string {
                return $node->attr('href');
            });
    }
}
