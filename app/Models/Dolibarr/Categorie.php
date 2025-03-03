<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = "llx_categorie";
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [
        'rowid',
        'entity',
        'fk_parent',
        'label',
        'ref_ext',
        'type',
        'description',
        'color',
        'fk_soc',
        'visible',
        'date_creation',
        'tms',
        'fk_user_creat',
        'fk_user_modif',
        'import_key',
    ];
}
