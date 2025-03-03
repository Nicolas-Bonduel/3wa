<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoliSyncRun extends Model
{
    protected $fillable = [
        'is_running',
        'last',
    ];

    public $timestamps = false;
}
