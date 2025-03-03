<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailcollectorEmailcollectorfilter extends Model
{
    use HasFactory;

    protected $table = 'llx_emailcollector_emailcollecteurfilter';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

}
