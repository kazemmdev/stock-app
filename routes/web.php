<?php

use App\Models\Stock;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Support\Facades\Route;


Route::get('/mail-preview', function () {
    $user = User::factory()->create();

    return (new ImportantStockUpdate(Stock::first()))->toMail($user);
});

Route::get('/', function () {
    return view('welcome');
});
