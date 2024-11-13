<?php

declare(strict_types=1);

namespace App\Client\Exceptions;

use App\Client\ApiClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionsToHttpExceptionsClientDecorator implements ApiClientInterface
{
    public function __construct(private ApiClientInterface $decoratedClient)
    {
    }

    public function request(ServerRequestInterface $request): PromiseInterface
    {
        $promise = $this->decoratedClient->request($request);
        return $promise->otherwise(function ($exception) {
            if ($exception instanceof RequestException) {
                throw new HttpException(
                    $exception->getResponse()?->getStatusCode() ?? 500,
                    $exception->getMessage(),
                    $exception
                );
            }
            throw $exception;
        });
    }
}
