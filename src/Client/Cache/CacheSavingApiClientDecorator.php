<?php

declare(strict_types=1);

namespace App\Client\Cache;

use App\Client\ApiClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Client\Cache\Key\ApiCacheKeyGeneratorInterface;

class CacheSavingApiClientDecorator implements ApiClientInterface
{
    public function __construct(
        private ApiClientInterface            $decoratedClient,
        private ApiCacheKeyGeneratorInterface $cacheKeyGenerator,
        private CacheItemPoolInterface        $cachePool,
    ) {
    }

    public function request(ServerRequestInterface $request): PromiseInterface
    {
        $promise = $this->decoratedClient->request($request);
        return $promise->then(
            function (ResponseInterface $response) use ($request) {
                $body = (string) $response->getBody();
                $cacheKey = $this->cacheKeyGenerator->generateKey($request);
                $cacheItem = $this->cachePool->getItem($cacheKey);

                $cacheItem->set(json_encode([
                    'body' => $body,
                ]));
                $cacheItem->expiresAt(new \DateTimeImmutable('+ 7 days'));
                $this->cachePool->save($cacheItem);

                return $response;
            }
        );
    }
}
