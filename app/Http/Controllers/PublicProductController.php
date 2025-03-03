<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    public function getProduct(string $slug, Request $request)
    {
        $product_master = Product::query()->where('name', $slug)->first();
        if (!$product_master) {
            abort(404);
        }
        $product_variation = $product_master->default_variation();

        return view('product', compact('product_master', 'product_variation'));
    }
}
