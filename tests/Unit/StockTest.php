<?php

namespace Tests\Unit;

use App\Clients\ClientException;
use App\Clients\StockResponse;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throw_exceptions_if_a_client_not_found_when_tracking()
    {
        $this->seed(RetailerWithProductSeeder::class);

        Retailer::first()->update(['name' => 'Foo Retailer']);

        $this->expectException(ClientException::class);

        Stock::first()->track();
    }

    /** @test */
    public function it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

//        ClientFactory::shouldReceive('make')->andReturn(new class implements Client {
//            public function checkAvailability(Stock $stock): StockResponse
//            {
//                return new StockResponse(true, 9900);
//            }
//        });

//        $clientMock = Mockery::mock(Client::class);
//        $clientMock->allows('checkAvailability')->andReturns(new StockResponse(true, 9900));
//
//        ClientFactory::shouldReceive('make')->andReturn($clientMock);

        ClientFactory::shouldReceive('make->checkAvailability')->andReturn(new StockResponse(true, 9900));

        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->in_stock);
        $this->assertEquals(9900, $stock->price);
    }
}
