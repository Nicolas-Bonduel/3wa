<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCustomerPriceLog extends Model
{
    use HasFactory;

    protected $table = 'llx_product_customer_price_log';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

}
