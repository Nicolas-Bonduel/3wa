<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExtrafields extends Model
{
    use HasFactory;

    protected $table = 'llx_product_extrafields';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [
    'rowid',
    'tms',
        'fk_object',
        'import_key',
        'cof7',
        'cof8',
        'marqueprincipale',
        'marquesecondaire',
        'categorie',
        'souscategorie',
        'gamme',
        'sousgame',
        'equiv',
        'associe',
        'fcpv',
        'grade',
        'findecommercialisation',
        'findeservice',
        'paysorigine2',
        'siteweb'
    ];

}
