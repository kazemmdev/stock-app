<?php

namespace App\Clients;

use App\Models\Stock;

interface Client
{
    public function checkAvailability(Stock $stock): StockResponse;
}
