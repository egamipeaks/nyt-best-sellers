<?php

use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('returns data from the best sellers client and caches the result', function () {
    $dummyData = getDummyEndpointResponseData();
    $service = getBestSellersService($dummyData);

    $params = ['author' => 'John Doe'];

    $result1 = $service->getBestSellers($params);

    ksort($params);
    $cacheKey = 'best_sellers_'.md5(json_encode($params));

    expect(Cache::has($cacheKey))->toBeTrue()
        ->and($result1)->toEqual($dummyData);

    $dummyDataModified = getDummyEndpointResponseData();
    $service = getBestSellersService($dummyDataModified);

    $result2 = $service->getBestSellers($params);
    expect($result2)->toEqual($dummyData);
});

it('returns uncached data with different parameters', function () {
    $dummyData = getDummyEndpointResponseData();
    $service = getBestSellersService($dummyData);

    $result1 = $service->getBestSellers(['author' => 'John Doe']);

    expect($result1)->toEqual($dummyData);

    $dummyDataModified = getDummyEndpointResponseData();
    $service = getBestSellersService($dummyDataModified);

    $result2 = $service->getBestSellers(['author' => 'Jane Doe']);

    expect($result1)->toEqual($dummyData)
        ->and($result2)->toEqual($dummyDataModified);
});

it('returns fresh data when the cache is cleared', function () {
    $dummyData = getDummyEndpointResponseData();
    $service = getBestSellersService($dummyData);

    $params = ['author' => 'John Doe'];

    $result1 = $service->getBestSellers($params);

    expect($result1)->toEqual($dummyData);

    Cache::flush();

    $dummyDataModified = getDummyEndpointResponseData();
    $service = getBestSellersService($dummyDataModified);

    $result2 = $service->getBestSellers($params);
    expect($result2)->toEqual($dummyDataModified);
});
