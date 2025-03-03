<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventorganizationConferenceorboothattendee extends Model
{
    use HasFactory;

    protected $table = 'llx_eventorganization_conferenceorboothatendee';
    protected $connection = 'dolibarr';
    protected $primaryKey = 'rowid';

    protected $fillable = [

    ];

    protected $casts = [];

}
