<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BestSellersRequest;
use App\Services\BestSellersService;
use Illuminate\Http\JsonResponse;

class BestSellersController extends Controller
{
    protected BestSellersService $bestSellersService;

    public function __construct(BestSellersService $service)
    {
        $this->bestSellersService = $service;
    }

    public function index(BestSellersRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $data = $this->bestSellersService->getBestSellers($validated);

        return response()->json($data);
    }
}
