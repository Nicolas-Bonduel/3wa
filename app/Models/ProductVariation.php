<?php

namespace App\Models;

use App\Services\DolibarrProductService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{

    protected $fillable = [
        'parent_id',
        'name',
        'sku',
        'price',
        'fk_dolibarr_id',
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    /**
     * Don't use it with a distant connection please...
     */
    public function stock(): int
    {
        return DolibarrProductService::getProductStock($this);
    }

}
