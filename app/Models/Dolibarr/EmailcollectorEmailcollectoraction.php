<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailcollectorEmailcollectoraction extends Model
{
    use HasFactory;

    protected $table = 'llx_emailcollector_emailcollecteuraction';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

}
