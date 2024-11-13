<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Promise\Utils;

class MultipleRequestsApiClient implements MultipleRequestsApiClientInterface
{
    public function __construct(private ApiClientInterface $apiClient)
    {
    }

    public function request(array $requests): array
    {
        $promises = array_map(fn ($request) => $this->apiClient->request($request), $requests);
        return Utils::all($promises)->wait();
    }
}
