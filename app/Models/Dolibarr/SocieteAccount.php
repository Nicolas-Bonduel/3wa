<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocieteAccount extends Model
{
    use HasFactory;

    protected $table = 'llx_societe_account';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

}
