<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        $response = $this->retailer->client()->checkAvailability($this);

        $this->update(['in_stock' => $response->available, 'price' => $response->price]);

        $this->recordHistory();
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    protected function recordHistory(): void
    {
        $this->histories()->create([
            'price' => $this->price,
            'in_stock' => $this->in_stock,
            'product_id' => $this->product_id,
        ]);
    }
}
