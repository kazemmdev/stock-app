<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_product_stock()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());

        Stock::first()->retailer->update(['name' => 'Target']);

        \Http::fake(fn() => ['available' => true, 'price' => 22000]);

        $this->artisan('track')->expectsOutput('All done!');

        $this->assertTrue(Product::first()->inStock());

    }
}
