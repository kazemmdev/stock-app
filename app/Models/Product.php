<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public function track()
    {
        $this->stock->each->track(
            fn($stock) => $this->recordHistory($stock)
        );
    }

    public function inStock(): bool
    {
        return $this->stock()->where('in_stock', true)->exists();
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function recordHistory(Stock $stock): void
    {
        $this->history()->create([
            'price' => $stock->price,
            'in_stock' => $stock->in_stock,
            'stock_id' => $stock->id,
        ]);
    }
}
