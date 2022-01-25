<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class TargetClient implements Client
{
    public function checkAvailability(Stock $stock) : StockResponse
    {
        $results =  Http::get('https://api.foo.com')->json();

        return new StockResponse(
            $results['available'],
            $results['price']
        );
    }
}
