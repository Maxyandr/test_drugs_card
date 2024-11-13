<?php

declare(strict_types=1);

namespace App\Client\Cache\Key;

use Psr\Http\Message\ServerRequestInterface;

class ApiCacheKeyGenerator implements ApiCacheKeyGeneratorInterface
{
    public function generateKey(ServerRequestInterface $request): string
    {
        return sprintf(
            'scrape_konning_%s_%s',
            md5($request->getUri()->getPath()),
            $request->getUri()->getQuery()
        );
    }
}
