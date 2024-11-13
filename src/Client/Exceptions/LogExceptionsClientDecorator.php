<?php

declare(strict_types=1);

namespace App\Client\Exceptions;

use App\Client\ApiClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class LogExceptionsClientDecorator implements ApiClientInterface
{
    public function __construct(
        private ApiClientInterface $decoratedClient,
        private LoggerInterface $logger
    ) {
    }

    public function request(ServerRequestInterface $request): PromiseInterface
    {
        $promise = $this->decoratedClient->request($request);
        return $promise->otherwise(function ($exception) {
            $this->logger->error($exception->getMessage());
            throw $exception;
        });
    }
}
