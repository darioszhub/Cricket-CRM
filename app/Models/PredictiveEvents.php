<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictiveEvents extends Model
{
    // Nome della tabella
    protected $table = 'PredictiveEvents';

    // Chiave primaria
    protected $primaryKey = 'IDEvent';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // La tabella non ha timestamps
    public $timestamps = false;

    protected $fillable = [
        'ServerEvent',
        'Spec',
    ];

    protected $casts = [
        'ServerEvent' => 'integer',
    ];

    // Relazione con PredictiveTODO tramite CodEvent
    public function predictiveTodoAsCodEvent()
    {
        return $this->hasMany(PredictiveTODO::class, 'CodEvent', 'IDEvent');
    }

    // Relazione con PredictiveEvents tramite Chain
    public function predictiveTodoAsChain()
    {
        return $this->hasMany(PredictiveTODO::class, 'Chain', 'IDEvent');
    }
}
