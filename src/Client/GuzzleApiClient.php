<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GuzzleApiClient implements ApiClientInterface
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function request(ServerRequestInterface $request): PromiseInterface
    {
        return $this->client->sendAsync($request, ['query' => $request->getQueryParams()]);
    }
}
