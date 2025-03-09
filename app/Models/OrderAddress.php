<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddress extends Model
{

    protected $fillable = [
        'order_id',
        'type',
        'name',
        'country',
        'address',
        'zip',
        'city',
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
