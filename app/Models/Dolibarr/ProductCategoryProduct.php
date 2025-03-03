<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryProduct extends Model
{


    protected $table = 'ec_product_category_product';

    protected $fillable = [
        'category_id',
        'product_id',
    ];

    public $timestamps = false;
}
