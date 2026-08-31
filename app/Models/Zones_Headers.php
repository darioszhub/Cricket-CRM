<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zones_Headers extends Model
{
    // Nome della tabella
    protected $table = 'Zones_Headers';

    // Chiave primaria
    protected $primaryKey = 'IDZone';

    // La chiave primaria non è autoincrementale
    public $incrementing = false;

    // Tipo della chiave primaria
    protected $keyType = 'string';

    // Disabilita i timestamp automatici di Laravel (se non necessari)
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Gestisce le modifiche sulla colonna TimestampEDT
    const UPDATED_AT = 'TimestampEDT';

    // Campi fillable
    protected $fillable = [
        'Description',
        'CodGroup',
        'Disabled',
        'Priority',
        'Group1',
        'Group2',
        'Group3',
        'Group4',
        'Group5',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    // Cast per le colonne
    protected $casts = [
        'CodGroup' => 'integer',
        'Priority' => 'integer',
        'Disabled' => 'boolean',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Relazione con Zones_Groups (CodGroup fa riferimento a IDZoneGroup)
    public function zoneGroup()
    {
        return $this->belongsTo(Zones_Groups::class, 'CodGroup', 'IDZoneGroup');
    }

    // Relazione con Zones_Criteria
    public function criteria()
    {
        return $this->hasMany(Zones_Criteria::class, 'CodZone', 'IDZone');
    }

    // Relazione con Agents_Assignments
    public function agents_assignments()
    {
        return $this->hasMany(Agents_Assignments::class, 'CodZone', 'IDZone');
    }

    // Relazione con Customers
    public function customers()
    {
        return $this->hasMany(Customers::class, 'CodZone', 'IDZone');
    }
}
