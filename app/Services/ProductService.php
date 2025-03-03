<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public static function get_products(array $filters = [], int $pagination = null)
    {
        $query = Product::query()->whereHas('variations');

        // filters
        if (isset($filters['subcategory']) && ! empty($filters['subcategory']))
            $query->whereIn('subcategory_id', array_keys($filters['subcategory']));
        if (isset($filters['brand']) && ! empty($filters['brand']))
            $query->whereIn('brand_id', array_keys($filters['brand']));
        if (isset($filters['range']) && ! empty($filters['range']))
            $query->whereIn('range_id', array_keys($filters['range']));

        // pagination
        if ($pagination && $pagination > 0)
            $products = $query->paginate($pagination);
        else
            $products = $query->get();

        // stocks
        $stocks = DolibarrProductService::getProductsStocks($products);
        $products->each(fn($p) => $p->stocks = $stocks[$p->id] );


        return $products;

    }

}
