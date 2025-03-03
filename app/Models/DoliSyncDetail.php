<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoliSyncDetail extends Model
{
    protected $fillable = [
        'sync_id',
        'rowid',
        'ref',
        'type',
    ];
}
