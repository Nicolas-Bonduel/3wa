<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieActioncomm extends Model
{
    use HasFactory;

    protected $table = "llx_categorie_actioncomm";
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

}
