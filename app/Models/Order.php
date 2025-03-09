<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{

    protected $fillable = [
        'customer_id',
        'code',
        'status',
        'comments',
        'files_in',
        'total',
        'amount',
        'tax_amount',
        'shipping_amount',
        'shipping_option',
        'coupon_amount',
        'coupon_code',
        'discount_amount',
        'discount_description',
        'payment_id',
        'mail_id',
        'mail_error',
        'seller_mail_id',
        'seller_mail_error',
        'fk_dolibarr_order_id',
    ];


    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

}
