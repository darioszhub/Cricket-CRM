<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredictiveTODO extends Model
{
    // Nome della tabella
    protected $table = 'PredictiveTODO';

    // Chiave primaria
    protected $primaryKey = 'IDTODO';

    // La primary key è incrementale
    public $incrementing = true;

    // La chiave primaria è un intero
    protected $keyType = 'int';

    // La tabella non ha timestamps
    public $timestamps = false;

    protected $fillable = [
        'CodEvent',
        'Chain',
        'ToReport',
        'Label',
        'Preset',
    ];

    protected $casts = [
        'CodEvent' => 'integer',
        'Chain' => 'integer',
        'ToReport' => 'integer',
    ];

    // Relazione con PredictiveEvents tramite CodEvent
    public function event()
    {
        return $this->belongsTo(PredictiveEvents::class, 'CodEvent', 'IDEvent');
    }

    // Relazione con PredictiveEvents tramite Chain
    public function chain()
    {
        return $this->belongsTo(PredictiveEvents::class, 'Chain', 'IDEvent');
    }

    // Relazione con Lists_States tramite ToReport
    /*  public function state()
    {
        return $this->belongsTo(Lists_States::class, 'ToReport', 'IDListState');
    } */
}
