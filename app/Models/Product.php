<?php

namespace App\Models;

use App\Services\DolibarrProductService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{

    protected $fillable = [
        'name',
        'sku',
        'description',
        'content',
        'images',
        'is_featured',
        'category_id',
        'subcategory_id',
        'brand_id',
        'range_id',
        'related',
        'fk_dolibarr_id',
    ];


    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'parent_id');
    }
    public function default_variation(): ?ProductVariation
    {
        $product_variations = $this->variations()->getResults();
        foreach ($product_variations as $product_variation) {
            if (str_ends_with($product_variation->sku, '_RECOND')) // default is recond
                return $product_variation;
        }

        return $product_variations->first(); // if no recond, take any one
    }
    public function variation(int $id): ?ProductVariation
    {
        return $this->variations()->getResults()->find($id);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubcategory::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(ProductBrand::class);
    }

    public function range(): BelongsTo
    {
        return $this->belongsTo(ProductRange::class);
    }


    public function stocks(): array
    {
        return DolibarrProductService::getProductStocks($this);
    }

}
