<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class StoreApiClient
{
    private const REQUEST_TIMEOUT = 10;

    private const CONNECTION_TIMEOUT = 5;

    public function __construct(
        private readonly Store $store,
    ) {
        if (blank($this->store->api_base_url)) {
            throw new InvalidArgumentException(
                'Aucune URL d’API n’est configurée pour ce magasin.'
            );
        }
    }

    public function request(): PendingRequest
    {
        return Http::baseUrl(
            rtrim($this->store->api_base_url, '/')
        )
            ->acceptJson()
            ->timeout(self::REQUEST_TIMEOUT)
            ->connectTimeout(self::CONNECTION_TIMEOUT);
    }
}