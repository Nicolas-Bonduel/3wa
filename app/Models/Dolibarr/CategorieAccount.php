<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieAccount extends Model
{
    use HasFactory;

    protected $table = "llx_categorie_account";
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

}
