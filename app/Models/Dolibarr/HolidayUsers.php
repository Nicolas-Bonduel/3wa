<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayUsers extends Model
{
    use HasFactory;

    protected $table = 'llx_holiday_users';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

}
