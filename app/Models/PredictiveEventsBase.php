<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictiveEventsBase extends Model
{
    // Nome della tabella
    protected $table = 'PredictiveEventsBase';

    // Chiave primaria
    protected $primaryKey = 'ServerEvent';

    // La chiave primaria non è incrementale
    public $incrementing = false;

    // La chiave primaria è un intero
    protected $keyType = 'int';

    // La tabella non ha timestamps
    public $timestamps = false;

    // Cast delle colonne
    protected $casts = [
        'ServerEvent' => 'integer',
        'Event' => 'string',
    ];
}
