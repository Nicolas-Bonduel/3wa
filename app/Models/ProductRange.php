<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductRange extends Model
{

    protected $fillable = [
        'ref',
        'label',
        'fk_dolibarr_id',
    ];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'range_id');
    }

}
