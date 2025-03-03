<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssociation extends Model
{
    use HasFactory;

    protected $table = 'llx_product_association';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    public function extrafields(){
        return $this->hasMany(ProductExtrafields::class,  'fk_object', 'rowid');
    }

}
