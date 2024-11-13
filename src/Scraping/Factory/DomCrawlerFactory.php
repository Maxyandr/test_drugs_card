<?php

declare(strict_types=1);

namespace App\Scraping\Factory;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

class DomCrawlerFactory
{
    public function createFromResponse(Response $response, string $uri): Crawler
    {
        return new Crawler((string)$response->getBody(), $uri);
    }
}
