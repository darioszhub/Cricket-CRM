<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zones_Groups extends Model
{
    // Nome della tabella
    protected $table = 'Zones_Groups';

    // Chiave primaria
    protected $primaryKey = 'IDZoneGroup';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Disabilita i timestamp automatici di Laravel (se non necessari)
    public $timestamps = false;

    // Campi fillable
    protected $fillable = [
        'Description',
    ];

    // Relazione con Zones_Headers
    public function zonesHeaders()
    {
        return $this->hasMany(Zones_Headers::class, 'CodGroup', 'IDZoneGroup');
    }
}
