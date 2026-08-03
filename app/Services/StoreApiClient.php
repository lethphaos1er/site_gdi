<?php

namespace App\Services;

use App\Exceptions\StoreApiConnectionException;
use App\Exceptions\StoreApiNotConfiguredException;
use App\Models\Store;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class StoreApiClient
{
    private const REQUEST_TIMEOUT = 10;

    private const CONNECTION_TIMEOUT = 5;

    public function __construct(
        private readonly Store $store,
    ) {
        if (blank($this->store->api_base_url)) {
            throw new StoreApiNotConfiguredException(
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

    public function get(string $endpoint, array $query = []): Response
    {
        try {
            return $this->request()
                ->get(
                    ltrim($endpoint, '/'),
                    $query
                )
                ->throw();
        } catch (ConnectionException $exception) {
            throw new StoreApiConnectionException(
                'Impossible de contacter l’API du magasin.',
                previous: $exception,
            );
        }
    }
}