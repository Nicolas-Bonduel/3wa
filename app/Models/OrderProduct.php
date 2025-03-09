<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{

    protected $fillable = [
        'order_id',
        'quantity',
        'name',
        'sku',
        'description',
        'content',
        'price',
        'category',
        'subcategory',
        'brand',
        'range',
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
