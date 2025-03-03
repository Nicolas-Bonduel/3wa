<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetTask extends Model
{
    use HasFactory;

    protected $table = 'llx_projet_task';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

}
