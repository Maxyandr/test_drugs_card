<?php

declare(strict_types=1);

namespace App\Scraping\Processor;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DomCrawler\Crawler;

class MenuItemsProcessor
{
    public function __construct(
        #[Autowire(param: 'app.scrape.main.domain')]
        private string               $domain,
    ) {

    }

    public function process(Crawler $crawler): array
    {
        $menuItemsSolo = $crawler
            ->filter('.categories-menu ul li a.btn.menu__item')
            ->each(function (Crawler $node, $i): string {
                return $node->attr('href');
            });

        $menuItemsMulti = $crawler
            ->filter('.categories-menu ul li .dropdown.open-on-hover ')
            ->each(function (Crawler $node, $i): array {
                $categoryId = $node->filter('button')->attr('data-id');

                if ($categoryId === '13') {
                    $categoryItemA = $node->filter('.dropdown-menu .container div a.dropdown-item');
                    $categoryItemList =$categoryItemA->each(
                        function (Crawler $node, $i) : string {
                            return $this->domain . $node->attr('href');
                        });
                } else {
                    $categoryItemList = $node->filter('.dropdown-menu .container a')
                        ->each(function (Crawler $node, $i): string {
                            return $node->attr('href');
                        });
                }
                return $categoryItemList;
            });

        return array_merge($menuItemsSolo, ...$menuItemsMulti);
    }
}
