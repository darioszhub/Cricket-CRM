<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists_States extends Model
{
    // Nome della tabella
    protected $table = 'Lists_States';

    // Chiave primaria
    protected $primaryKey = 'IDListState';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Disabilita timestamps (non gestiti da laravel)
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    // Campi modificabili
    protected $fillable = [
        'Description',
        'Group',
        'LTYPE',
        'Disabled',
        'FreeField1',
        'FreeField2',
        'FreeField3',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    // Cast per i tipi di dati
    protected $casts = [
        'Disabled' => 'boolean',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Relazione con Lists
    public function lists()
    {
        return $this->hasMany(Lists::class, 'CodListState', 'IDListState');
    }

    // Relazione con Calls_States
    public function callsStates()
    {
        return $this->hasMany(Calls_States::class, 'CodListState', 'IDListState');
    }
}
