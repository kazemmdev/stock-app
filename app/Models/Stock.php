<?php

namespace App\Models;

use App\Events\NowInStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track($callback = null)
    {
        $response = $this->retailer->client()->checkAvailability($this);

        if (!$this->in_stock && $response->available) {
            event(new NowInStock($this));
        }

        $this->update(['in_stock' => $response->available, 'price' => $response->price]);

        $callback && $callback($this);
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
