<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieProduct extends Model
{
    use HasFactory;

    protected $table = "llx_categorie_product";
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

}
