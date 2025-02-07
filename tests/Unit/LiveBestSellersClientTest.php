<?php

use App\Clients\BestSellers\LiveBestSellersClient;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    disableNytTestMode();
});

it('returns valid JSON data when the API call succeeds', function () {
    fakeBestSellersData();

    $client = new LiveBestSellersClient;
    $result = $client->getBestSellers(['author' => 'Jane Doe']);

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('status', 'OK');
});

it('aborts when the API call fails', function () {
    fakeErrorResponse();

    $client = new LiveBestSellersClient;

    $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
    $client->getBestSellers(['author' => 'Jane Doe']);
});

it('converts ISBN array into a semicolon-delimited string', function () {
    fakeBestSellersData();

    $client = new LiveBestSellersClient;
    $client->getBestSellers(['isbn' => ['1234567890', '1234567890123']]);

    Http::assertSent(function ($request) {
        return $request['isbn'] === '1234567890;1234567890123';
    });
});
