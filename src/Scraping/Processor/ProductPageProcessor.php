<?php

declare(strict_types=1);

namespace App\Scraping\Processor;

use App\Scraping\Model\PageItem\Description;
use App\Scraping\Model\PageItem\Feature;
use App\Scraping\Model\PageItem\Image;
use App\Scraping\Model\PageItem\MainAttribute;
use App\Scraping\Model\PageItem\MainProductData;
use App\Scraping\Model\PageItem\Specification;
use App\Scraping\Model\PageItem\Support;
use App\Scraping\Model\PageItem\VideoReview;
use App\Scraping\Model\ProductItem;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use function Symfony\Component\String\u;

class ProductPageProcessor
{
    private const DEFAULT_EMPTY_TEXT_PLACEHOLDER = '';

    public function __construct(
        private readonly LoggerInterface  $logger,
    ) {
    }

    /**
     * @param Crawler $productItem
     * @return ProductItem
     */
    public function process(Crawler $crawler): ProductItem
    {
        $this->logger->debug('Saving ' . $crawler->getUri());
        $productItem = new ProductItem($crawler->getUri());
        try {
            $productItem->setDescription($this->getDescription($crawler));
            $productItem->setSpecifications($this->getCharacteristics($crawler));
            $productItem->setSupport($this->getSupport($crawler));
            $productItem->setMainProductData($this->getMainProductData($crawler));
            $productItem->setVideoReviews($this->getVideoReviews($crawler));
            $productItem->setTitle($this->getTitle($crawler));
            $productItem->setProductType($this->getProductType($crawler));
        } catch (\Exception $exception) {
            $this->logger->critical(
                sprintf('%s url %s product %s',
                    $exception->getMessage(),
                    $productItem->getUrl(),
                    json_encode($productItem),
                )
            );
            throw $exception;
        }

        return $productItem;
    }

    private function getDescription(Crawler $crawler): Description
    {
        $this->logger->debug(__METHOD__);
        $path = trim(parse_url($crawler->filter('link[rel=canonical]')->attr('href'), PHP_URL_PATH));
        $path = explode('/', $path);
        $handle = end($path);

        $features = $crawler->filter('#description div.product-features div.product-feature')->each(function (Crawler $node, $i) use ($handle): Feature {
            $title = $node->filter('div.feature-content h3')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER);
            $title = (string)u($title)->lower()->title(true);
            $systemTitle = $title . '-' . $handle;
            $systemTitle = str_replace('_', ' ', (string)u($systemTitle)->snake());
            return new Feature(
                $title,
                $node->filter('div.feature-content div.feature-description p')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
                $systemTitle,
                new Image(
                    $node->filter('div.feature-photo a img')->attr('alt', self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
                    $node->filter('div.feature-photo a img')->attr('src', self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
                ),
            );
        });

        return new Description(
            $crawler->filter('#description div.product-description p')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
            $features,
        );
    }

    private function getCharacteristics(Crawler $crawler): array
    {
        $this->logger->debug(__METHOD__);

        return $crawler->filter('#specifications div.attributes div.attribute')->each(function (Crawler $node, $i): Specification {
            return new Specification(
                $node->filter('div:first-child')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
                $node->filter('div:last-child')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
            );
        });
    }

    private function getPrice(Crawler $crawler): string
    {
        return $crawler->filter('div.price p.value')->text('price');
    }

    private function getSupport(Crawler $crawler): array
    {
        $this->logger->debug(__METHOD__);

        return $crawler->filter('#support div.covers div.cover')->each(function (Crawler $node, $i): Support {
            return new Support(
                $node->filter('a.thumb')->attr('href'),
                $node->filter('a.thumb span.pdf-title')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
                new Image(
                    $node->filter('a.thumb img')->attr('alt'),
                    $node->filter('a.thumb img')->attr('src'),
                ),
            );
        });
    }

    private function getMainAtrributes(Crawler $crawler): array
    {
        $this->logger->debug(__METHOD__);

        return $crawler->filter('.product-main .item-attributes tr.attribute')->each(function (Crawler $node, $i): MainAttribute {
            return new MainAttribute(
                $node->filter('td:first-child')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
                $node->filter('td+td')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER),
            );
        });
    }

    private function getVideoReviews(Crawler $crawler): array
    {
        $this->logger->debug(__METHOD__);

        return $crawler->filter('#videos .videos div')->each(function (Crawler $node, $i): VideoReview {
            return new VideoReview(
                $node->filter('iframe')->attr('src'),
            );
        });
    }

    private function getImages(Crawler $crawler): array
    {
        $this->logger->debug(__METHOD__);

        return $crawler->filter('#productGallery a.single-item')->each(function (Crawler $node, $i): Image {
            return new Image(
                $node->filter('img')->attr('alt'),
                $node->filter('img')->attr('src'),
            );
        });
    }

    private function getTitle(Crawler $crawler): string
    {
        $this->logger->debug(__METHOD__);

        return $crawler->filter('section.product-page h1')->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER);
    }

    private function getMainProductData(Crawler $crawler): MainProductData
    {
        $mainProductData = new MainProductData();
        $mainProductData->setImages($this->getImages($crawler));
        $mainProductData->setPrice($this->getPrice($crawler));
        $mainProductData->setTour360($this->getTour360($crawler));
        return $mainProductData;
    }

    private function getProductType(Crawler $crawler): string
    {
        $this->logger->debug(__METHOD__);

        return $crawler
            ->filter('section.product-page ul.breadcrumb :nth-child(3)')
            ->text(self::DEFAULT_EMPTY_TEXT_PLACEHOLDER);
    }

    private function getTour360(Crawler $crawler): string
    {
        return $crawler
            ->filter('div.product-gallery div.plus-3d a.btn-3d')
            ->attr('href', self::DEFAULT_EMPTY_TEXT_PLACEHOLDER);
    }
}
