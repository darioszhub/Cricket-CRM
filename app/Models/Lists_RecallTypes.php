<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists_RecallTypes extends Model
{
    // Nome della tabella
    protected $table = 'Lists_RecallTypes';

    // Chiave primaria
    protected $primaryKey = 'IDRecallType';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    // Campi che possono essere riempiti
    protected $fillable = [
        'Description',
        'Disabled',
        'CodUserLastEdit',
        'isPublicRecall',
    ];

    // Casting dei tipi di dati
    protected $casts = [
        'Disabled' => 'boolean',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
        'isPublicRecall' => 'boolean',
    ];

    // Relazione con Lists
    public function lists()
    {
        return $this->hasMany(Lists::class, 'CodRecallType', 'IDRecallType');
    }

    // Relazione con Calls_States
    public function callsStates()
    {
        return $this->hasMany(Calls_States::class, 'CodRecallType', 'IDRecallType');
    }
}
