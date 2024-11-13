<?php

declare(strict_types=1);

namespace App\Scraping;

use App\Client\ApiClientInterface;
use App\Client\MultipleRequestsApiClientInterface;
use App\Client\ServerRequest\ServerRequestFactory;
use App\Scraping\Factory\DomCrawlerFactory;
use App\Scraping\Model\Languages;
use App\Scraping\Model\ProductItem;
use App\Scraping\Processor\LanguageUrlsProcessor;
use App\Scraping\Processor\MenuItemsProcessor;
use App\Scraping\Processor\ProductPageProcessor;
use App\Scraping\Processor\ProductUrlsProcessor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ScrapingProvider
{
    const MAX_SCRAPING_PRODUCT_PAGES = 3;

    public function __construct(
        private ApiClientInterface                 $client,
        private ServerRequestFactory               $serverRequestFactory,
        private DomCrawlerFactory                  $crawlerFactory,
        private ProductPageProcessor               $productPageProcessor,
        private LanguageUrlsProcessor              $languageUrlsProcessor,
        private MenuItemsProcessor                 $menuItemsProcessor,
        private MultipleRequestsApiClientInterface $multipleRequestsApiClient,
        private ProductUrlsProcessor               $productUrlsProcessor,
        #[Autowire(param: 'app.scrape.main.domain')]
        private string                             $domain,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function processProductUrl(string $url): ProductItem
    {
        $response = $this->client->request($this->serverRequestFactory->create($url))->wait();
        $crawler = $this->crawlerFactory->createFromResponse($response, $url);
        return $this->productPageProcessor->process($crawler);
    }


    public function scrapeProductData(string $lang, array $except): array
    {
        $products = [];
        // Get list of language urls
        $response = $this->client->request($this->serverRequestFactory->create($this->domain))->wait();
        $crawler = $this->crawlerFactory->createFromResponse($response, $this->domain);
        $languageUrls = $this->languageUrlsProcessor->processor($crawler);

        /** @var Languages $languageUrl */
        foreach ($languageUrls as $languageUrl) {
            $thatLang = $languageUrl->getLanguageCode();
            if (isset($lang) && $lang !== $thatLang || in_array($thatLang, $except, true)) {
                continue;
            }

            $categoryUrls = $this->getCategoryUrlsByLangUrl($languageUrl->getUrl());
            $productUrls = $this->getProductUrls($categoryUrls);
            $products = $this->getProductData($productUrls);
        }

        return $products;
    }

    private function getCategoryUrlsByLangUrl(string $langUrl): array
    {
        $response = $this->client->request($this->serverRequestFactory->create($langUrl))->wait();
        $crawler = $this->crawlerFactory->createFromResponse($response, $langUrl);
        return $this->menuItemsProcessor->process($crawler);
    }

    private function getProductUrls(array $categoryUrls): array
    {
        $requests = [];
        $i = 0;
        foreach ($categoryUrls as $categoryUrl) {
            $i++;
            $requests[] = $this->serverRequestFactory->create($categoryUrl);
            if ($i >= self::MAX_SCRAPING_PRODUCT_PAGES) {
                break;
            }
        }

        $responses = $this->multipleRequestsApiClient->request($requests);

        $productUrls = [];
        foreach ($responses as $key => $response) {
            $crawler = $this->crawlerFactory->createFromResponse($response, $categoryUrls[$key]);
            $productUrls = array_merge($productUrls, $this->productUrlsProcessor->process($crawler));
        }
        return $productUrls;
    }

    private function getProductData(array $productUrls): array
    {
        $requests = [];
        $i = 0;
        foreach ($productUrls as $productUrl) {
            $i++;
            $requests[] = $this->serverRequestFactory->create($productUrl);
            if ($i >= self::MAX_SCRAPING_PRODUCT_PAGES) {
                break;
            }
        }
        $responses = $this->multipleRequestsApiClient->request($requests);

        $products = [];
        foreach ($responses as $key => $response) {
            $crawler = $this->crawlerFactory->createFromResponse($response, $productUrls[$key]);
            $products[] = $this->productPageProcessor->process($crawler);
        }
        return $products;
    }
}
