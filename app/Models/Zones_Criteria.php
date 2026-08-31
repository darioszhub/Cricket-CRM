<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zones_Criteria extends Model
{
    // Nome della tabella
    protected $table = 'Zones_Criteria';

    // Chiave primaria
    protected $primaryKey = 'IDCriteria';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Disabilita i timestamp automatici di Laravel
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'CodZone',
        'Disabled',
        'ZoneType',
        'Value',
        'Value2',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    // Cast dei campi
    protected $casts = [
        'Disabled' => 'boolean',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Relazione con la tabella Zones_Headers
    public function zone()
    {
        return $this->belongsTo(Zones_Headers::class, 'CodZone', 'IDZone');
    }
}
