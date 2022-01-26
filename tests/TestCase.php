<?php

namespace Tests;

use App\Clients\StockResponse;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function mockClientFactory($available = true, $price = 29900)
    {
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


        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new StockResponse($available, $price));
    }

}
