<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSubcategory extends Model
{

    protected $fillable = [
        'category_id',
        'ref',
        'label',
        'fk_dolibarr_id',
    ];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

}
