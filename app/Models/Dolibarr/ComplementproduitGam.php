<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplementproduitGam extends Model
{
    use HasFactory;

    protected $table = 'llx_complementproduit_gam';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

    public function count_by_products($categories = [], $brands = [], $sousgammes = []){

        /*
         * a partir d'une listede produits, combien j'ai de produit avec la bonne gamme (self) ?
         */
        $query = \Botble\Ecommerce\Models\Product::query()->where('gamme', $this->label);

//dump($categories, $brands, $sousgammes );
        if(count($categories) > 0){
            $query = $query->whereHas('categories', function($q) use ($categories){
                return $q->whereIn('id', $categories);
            });
        }

        if(count($brands) > 0){
            $query = $query->whereHas('brand', function($q) use ($brands){
                return $q->whereIn('name', $brands);
            });
        }

clock($query->toSql());
        $nbr = $query->count();

        return $nbr;
    }

}
