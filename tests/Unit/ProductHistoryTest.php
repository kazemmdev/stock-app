<?php

namespace Tests\Unit;

use App\Clients\StockResponse;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;
use Database\Seeders\RetailerWithProductSeeder;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProductHistoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_records_history_on_tracking_product()
    {
        $this->seed(RetailerWithProductSeeder::class);

//        \Http::fake(fn() => ['salePrice' => 99, 'onlineAvailability' => true]);

        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockResponse($available = true, $price = 99));

        $product = tap(Product::first(), function ($product) {

            $this->assertCount(0, $product->history);

            $product->track();

            $this->assertCount(1, $product->refresh()->history);

        });

        $history = $product->history()->first();

        $this->assertEquals($price, $history->price);
        $this->assertEquals($available, $history->in_stock);
        $this->assertEquals($product->id, $history->product_id);
        $this->assertEquals($product->stock[0]->id, $history->stock_id);
    }

    /** @test */
    public function it_notifies_the_user_when_the_stock_available()
    {
        Notification::fake();

        $this->seed(RetailerWithProductSeeder::class);

        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockResponse($available = true, $price = 99));

        $this->artisan('track');

        Notification::assertSentTo(User::first(), ImportantStockUpdate::class);
    }

    /** @test */
    public function it_does_not_notifies_the_user_when_the_stock_remain_unavailable()
    {
        Notification::fake();

        $this->seed(RetailerWithProductSeeder::class);

        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockResponse($available = false, $price = 99));

        $this->artisan('track');

        Notification::assertNothingSent();
    }

}
