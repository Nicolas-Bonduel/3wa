<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesExtrafields extends Model
{
    use HasFactory;

    protected $table = "llx_categories_extrafields";
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    public function extrafields(){
        return $this->hasManyThrough(Extrafields::class, CategoriesExtrafields::class);
    }

}
