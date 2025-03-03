<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoliSyncDiff extends Model
{
    protected $fillable = [
        'sync_id',
        'rowid',
        'ref',
        'type',
        'tables',
        'changes',
        'value_changes',
    ];
}
