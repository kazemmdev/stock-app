<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProductHistoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->seed(RetailerWithProductSeeder::class);
    }

    /** @test */
    public function it_can_records_history_on_tracking_product()
    {
        $this->mockClientFactory($available = true, $price = 2990);

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
        $this->mockClientFactory();

        $this->artisan('track');

        Notification::assertSentTo(User::first(), ImportantStockUpdate::class);
    }

    /** @test */
    public function it_does_not_notifies_the_user_when_the_stock_remain_unavailable()
    {
        $this->mockClientFactory(false);

        $this->artisan('track');

        Notification::assertNothingSent();
    }

}
