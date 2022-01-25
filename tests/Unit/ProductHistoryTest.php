<?php

namespace Tests\Unit;

use App\Models\History;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_records_history_on_tracking_product()
    {
        $this->seed(RetailerWithProductSeeder::class);

        \Http::fake(fn() => ['salePrice' => 299, 'onlineAvailability' => true]);

        $this->assertEquals(0, History::count());

        $stock = tap(Stock::first())->track();

        $this->assertEquals(1, History::count());

        $history = History::first();

        $this->assertEquals($stock->price, $history->price);
        $this->assertEquals($stock->in_stock, $history->in_stock);
        $this->assertEquals($stock->product_id, $history->product_id);
        $this->assertEquals($stock->id, $history->stock_id);
    }

}
