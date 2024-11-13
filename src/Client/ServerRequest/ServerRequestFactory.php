<?php

declare(strict_types=1);

namespace App\Client\ServerRequest;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestFactory
{
    public function create(string $url, array $queryParams=[]): ServerRequestInterface
    {
        $request = new ServerRequest('GET', $url);
        if (!empty($queryParams)) {
            return $request->withQueryParams($queryParams);
        }
        return $request;
    }
}
