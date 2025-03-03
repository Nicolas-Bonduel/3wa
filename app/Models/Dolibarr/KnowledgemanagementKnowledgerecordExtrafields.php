<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgemanagementKnowledgerecordExtrafields extends Model
{
    use HasFactory;

    protected $table = 'llx_knowledgemanagement_knowledgerecord_extrafields';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

}
