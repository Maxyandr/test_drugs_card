<?php

declare(strict_types=1);

namespace App\Client\Cache;

use App\Client\ApiClientInterface;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use App\Client\Cache\Key\ApiCacheKeyGeneratorInterface;

class CacheGettingApiClientDecorator implements ApiClientInterface
{
    public function __construct(
        private ApiClientInterface            $decoratedClient,
        private ApiCacheKeyGeneratorInterface $cacheKeyGenerator,
        private CacheItemPoolInterface        $cachePool,
    ) {
    }

    public function request(ServerRequestInterface $request): PromiseInterface
    {
        $promise =  new FulfilledPromise(null);

        return $promise->then(function () use ($request) {
            $cacheKey = $this->cacheKeyGenerator->generateKey($request);
            $cacheItem = $this->cachePool->getItem($cacheKey);

            if ($cacheItem->isHit()) {
                return $this->createResponseFromCacheItem($cacheItem);
            }

            return $this->decoratedClient->request($request)
                ->otherwise(function ($exception) use ($cacheItem) {
                    if ($cacheItem->isHit()) {
                        return $this->createResponseFromCacheItem($cacheItem);
                    }
                    throw $exception;
                });
        });
    }

    private function createResponseFromCacheItem(CacheItemInterface $cacheItem): Response
    {
        $responseData = json_decode($cacheItem->get(), true);
        return new Response(200, [], $responseData['body']);
    }
}
