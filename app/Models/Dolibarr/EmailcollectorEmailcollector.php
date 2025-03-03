<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailcollectorEmailcollector extends Model
{
    use HasFactory;

    protected $table = 'llx_emailcollector_emailcollecteur';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [
        
    ];

}
