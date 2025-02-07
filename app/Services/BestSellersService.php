<?php

namespace App\Services;

use App\Contracts\BestSellersClientContract;
use Illuminate\Support\Facades\Cache;

class BestSellersService
{
    protected BestSellersClientContract $client;

    public function __construct(BestSellersClientContract $client)
    {
        $this->client = $client;
    }

    public function getBestSellers(array $params): array
    {
        return Cache::remember(
            $this->getCacheKey($params),
            now()->addDay(),
            fn () => $this->client->getBestSellers($params)
        );
    }

    protected function getCacheKey(array $params): string
    {
        ksort($params);

        return 'best_sellers_'.md5(json_encode($params));
    }
}
