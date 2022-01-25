<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class BestBuyClient implements Client
{
    public function checkAvailability(Stock $stock): StockResponse
    {
        $results = Http::get($this->endPoint($stock->sku))->json();

        return new StockResponse(
            $results['onlineAvailability'],
            $this->dollarToCents($results['salePrice'])
        );
    }

    public function endPoint($sku): string
    {
        $key = config('services.clients.bestBuy.key');

        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }

    protected function dollarToCents($salePrice): int
    {
        return (int)($salePrice * 100);
    }
}
