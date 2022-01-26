<?php

namespace App\UseCases;

use App\Clients\StockResponse;
use App\Models\History;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Foundation\Bus\Dispatchable;

class TrackStock
{
    use Dispatchable;

    protected Stock $stock;

    protected StockResponse $response;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function handle()
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordHistory();
    }

    protected function checkAvailability()
    {
        $this->response = $this->stock->retailer->client()
            ->checkAvailability($this->stock);
    }

    protected function notifyUser()
    {
        if ($this->isNowInStock())
            User::first()->notify(new ImportantStockUpdate($this->stock));
    }

    protected function refreshStock()
    {
        $this->stock->update([
            'in_stock' => $this->response->available,
            'price' => $this->response->price
        ]);
    }

    protected function recordHistory()
    {
        History::create([
            'price' => $this->stock->price,
            'in_stock' => $this->stock->in_stock,
            'stock_id' => $this->stock->id,
            'product_id' => $this->stock->product_id,
        ]);
    }

    protected function isNowInStock(): bool
    {
        return !$this->stock->in_stock && $this->response->available;
    }
}
