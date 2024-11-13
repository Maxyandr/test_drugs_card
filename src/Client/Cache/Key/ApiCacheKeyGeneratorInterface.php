<?php

declare(strict_types=1);

namespace App\Client\Cache\Key;

use Psr\Http\Message\ServerRequestInterface;

interface ApiCacheKeyGeneratorInterface
{
    public function generateKey(ServerRequestInterface $request): string;
}
