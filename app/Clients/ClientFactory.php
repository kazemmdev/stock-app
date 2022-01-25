<?php

namespace App\Clients;

use App\Models\Retailer;
use Illuminate\Support\Str;

class ClientFactory
{
    public function make(Retailer $retailer) : Client
    {
        $class = "App\\Clients\\" . Str::studly($retailer->name) . "Client";

        if (!class_exists($class)) {
            throw ClientException::create($retailer);
        }

        return new $class;
    }
}
