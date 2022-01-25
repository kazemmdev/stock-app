<?php

namespace App\Listeners;

use App\Events\NowInStock;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;

class SendStockUpdateNotification
{
    public function handle(NowInStock $event)
    {
        User::first()->notify(new ImportantStockUpdate());
    }
}
