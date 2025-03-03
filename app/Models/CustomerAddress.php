<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerAddress extends Model
{

    protected $fillable = [
        'customer_id',
        'name',
        'country',
        'address',
        'zip',
        'city',
        'is_default',
        'fk_dolibarr_societe',
        'fk_dolibarr_socpeople'
    ];


    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id');
    }

}
