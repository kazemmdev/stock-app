<?php

namespace App\Events;

use App\Models\Stock;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NowInStock
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Stock $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }
}
