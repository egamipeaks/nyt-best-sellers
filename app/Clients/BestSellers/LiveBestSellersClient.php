<?php

namespace App\Clients\BestSellers;

use App\Contracts\BestSellersClientContract;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class LiveBestSellersClient implements BestSellersClientContract
{
    const TIMEOUT_SECONDS = 5;

    const RETRIES = 3;

    const RETRY_DELAY_MS = 100;

    protected string $apiKey;

    protected string $url;

    protected string $path = '/books/v3/lists/best-sellers/history.json';

    public function __construct()
    {
        $this->apiKey = config('services.nyt.api_key');
        $this->url = config('services.nyt.base_url').$this->path;
    }

    public function getBestSellers(array $params): array
    {
        try {
            $queryParams = $this->prepareQueryParams($params);

            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->retry(self::RETRIES, self::RETRY_DELAY_MS)
                ->get($this->url, $queryParams)
                ->throw();

            return $response->json();

        } catch (RequestException $e) {
            logger()->error('Best Sellers API Error: '.$e->getMessage());
            abort(502, 'Failed to fetch data from the NYT API.');
        }
    }

    private function prepareQueryParams(array $params): array
    {
        $params = array_filter($params);

        if (isset($params['isbn']) && is_array($params['isbn'])) {
            $params['isbn'] = implode(';', $params['isbn']);
        }

        $params['api-key'] = $this->apiKey;

        return $params;
    }
}
