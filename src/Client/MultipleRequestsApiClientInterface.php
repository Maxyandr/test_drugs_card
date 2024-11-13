<?php

declare(strict_types=1);

namespace App\Client;

interface MultipleRequestsApiClientInterface
{
    public function request(array $requests): array;
}
