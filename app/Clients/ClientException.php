<?php

namespace App\Clients;

use App\Models\Retailer;
use Exception;

class ClientException extends Exception
{
    public static function create(Retailer $retailer) : self
    {
        return new static('Client class not found for ' . $retailer->name);
    }
}
