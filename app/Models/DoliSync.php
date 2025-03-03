<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoliSync extends Model
{
    protected $fillable = [
        'error',
        'warnings',
        'up_amount',
        'up_row_ids',
        'add_amount',
        'add_row_ids',
        'del_amount',
        'del_row_ids',
        'logs',
    ];
}
