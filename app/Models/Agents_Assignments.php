<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agents_Assignments extends Model
{
    // Nome della tabella
    protected $table = 'Agents_Assignments';

    // Nessuna chiave primaria singola
    protected $primaryKey = null;
    public $incrementing = false;

    // Timestamp gestiti manualmente
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampIns';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEdt';


    // Fillable
    protected $fillable = [
        'CodZone',
        'CodAgent',
        'Disabled',
        'CodPortfolio',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    // Cast
    protected $casts = [
        'CodAgent' => 'integer',
        'Disabled' => 'boolean',
        'CodPortfolio' => 'integer',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Relazione con Portfolios
    public function portfolio()
    {
        return $this->belongsTo(Portfolios::class, 'CodPortfolio', 'IDPortfolio');
    }

    // Relazione con Agents
    public function agent()
    {
        return $this->belongsTo(Agents::class, 'CodAgent', 'IDAgent');
    }

    // Relazione con Zones_Headers
    public function zone()
    {
        return $this->belongsTo(Zones_Headers::class, 'CodZone', 'IDZone');
    }
}
