<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extrafields extends Model
{
    use HasFactory;

    protected $table = 'llx_extrafields';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [
        'rowid', //  37,
        'name', //  'dbutsaisiedonnescofiem',
        'entity', //  1,
        'elementtype', //  'product',
        'label', //  '-- Début saisie données Cofiem',
        'type', //  'separate',
        'size', //  '',
        'fieldcomputed', //  null,
        'fielddefault', //  null,
        'fieldunique', //  0,
        'fieldrequired', //  0,
        'perms', //  null,
        'enabled', //  '1',
        'pos', //  50,
        'alwayseditable', //  0,
        'param', //  'a:1:{s:7:'options';a:1:{s:0:'';N;}}',
        'list', //  '3',
        'printable', //  0,
        'totalizable', //  0,
        'langs', //  null,
        'help', //  null,
        'css', //  null,
        'cssview', //  null,
        'csslist', //  null,
        'fk_user_author', //  1,
        'fk_user_modif', //  1,
        'datec', //  '2023-06-06 17:13:22',
        'tms', //  '2023-06-06 15:13:22',

    ];

}
