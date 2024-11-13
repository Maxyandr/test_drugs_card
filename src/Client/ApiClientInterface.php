<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ApiClientInterface
{
    public function request(ServerRequestInterface $request): PromiseInterface;
}
