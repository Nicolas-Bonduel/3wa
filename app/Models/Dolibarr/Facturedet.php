<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturedet extends Model
{
    use HasFactory;

    protected $table = 'llx_facturedet';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

}
