<?php

namespace App\Contracts;

interface BestSellersClientContract
{
    public function getBestSellers(array $params): array;
}
