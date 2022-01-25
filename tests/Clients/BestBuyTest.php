<?php

namespace Tests\Clients;

use App\Clients\BestBuyClient;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group api
 */
class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_track_a_product()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $stock = tap(Stock::first())->update([
            'sku' => '6477885',
            'url' => 'https://www.bestbuy.com/site/hp-15-6-laptop-intel-celeron-4gb-memory-128gb-ssd-natural-silver/6477885.p?skuId=6477885'
        ]);

        try {
            (new BestBuyClient())->checkAvailability($stock);
        } catch (\Exception $e) {
            $this->fail('Failed to track the BestBuy API properly, ' . $e->getMessage());
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function it_track_proper_api_response()
    {
        \Http::fake(fn() => ['salePrice' => 299.99, 'onlineAvailability' => true]);

        $response = (new BestBuyClient())->checkAvailability(new Stock());

        $this->assertEquals(29999, $response->price);
    }
}
