<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls_WhyNot extends Model
{
    // Nome della tabella
    protected $table = 'Calls_WhyNot';

    // Disabilita autoincremento e chiave composta
    protected $primaryKey = null;
    public $incrementing = false;

    // Disabilita i timestamp automatici di Laravel (se non necessari)
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Gestisce le modifiche sulla colonna TimestampEDT
    const UPDATED_AT = 'TimestampEDT';


    // Attributi assegnabili
    protected $fillable = [
        'WhyNot',
        'WNTYPE',
        'CodHeader',
        'Disabled',
        'CodLastUserEdit',
    ];

    // Cast dei tipi
    protected $casts = [
        'Disabled' => 'boolean',
        'CodHeader' => 'boolean',
        'TimestampIns' => 'datetime',
        'TimestampEdt' => 'datetime',
    ];

    // Relazione con Lists_Headers
    public function header()
    {
        return $this->belongsTo(Lists_Headers::class, 'CodHeader', 'IDHeader');
    }
}
