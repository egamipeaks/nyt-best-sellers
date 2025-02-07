<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Contracts\BestSellersClientContract;
use App\Models\User;
use App\Rules\IsbnRule;
use App\Services\BestSellersService;
use Illuminate\Support\Facades\Http;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

pest()->use(Tests\TestCase::class)->in('Unit');

function disableNytTestMode(): void
{
    config(['services.nyt.test_mode' => false]);
}

function enableNytTestMode(): void
{
    config(['services.nyt.test_mode' => true]);
}

function fakeBestSellersData(): void
{
    Http::fake([
        'api.nytimes.com/*' => Http::response([
            'status' => 'OK',
            'results' => [['title' => 'Book 1']],
        ], 200),
    ]);
}

function fakeErrorResponse($code = 500): void
{
    Http::fake([
        'api.nytimes.com/*' => Http::response(null, $code),
    ]);
}

function getDummyEndpointResponseData(): array
{
    return [
        'status' => 'OK',
        'results' => [
            ['title' => fake()->sentence(3)],
        ],
    ];
}

function getDummyBestSellersClient(array $dummyData): BestSellersClientContract
{
    return new class($dummyData) implements BestSellersClientContract
    {
        private $dummyData;

        public function __construct($dummyData)
        {
            $this->dummyData = $dummyData;
        }

        public function getBestSellers(array $params): array
        {
            return $this->dummyData;
        }
    };
}

function getBestSellersService(array $dummyData): BestSellersService
{
    return new BestSellersService(getDummyBestSellersClient($dummyData));
}

function getEndpointUrl($query = []): string
{
    return '/api/v1/best-sellers?'.http_build_query($query);
}

function getTestUser(): User
{
    return User::factory()->create();
}

function validateIsbn(string $isbn, bool $valid): void
{
    $rule = new IsbnRule;

    $failCalled = false;
    $fail = function ($message) use (&$failCalled) {
        $failCalled = true;
    };

    $rule->validate('isbn', $isbn, $fail);
    expect($failCalled)->toBe(! $valid);
}
