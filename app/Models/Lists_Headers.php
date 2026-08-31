<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists_Headers extends Model
{
    // Nome tabella
    protected $table = 'Lists_Headers';

    // Chiave primaria
    protected $primaryKey = 'IDHeader';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Timestamp gestiti manualmente
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampIns';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEdt';


    // Attributi assegnabili
    protected $fillable = [
        'CodHeaderParent',
        'Header',
        'Description',
        'JobType',
        'Disabled',
        'DateStart',
        'DateStop',
        'CodPortfolio',
        'BindsToArea',
        'UseTrigger',
        'AllowPublicRecalls',
        'NORESPONSE_Timeout',
        'NORESPONSE_PublicRecall',
        'NORESPONSE_CodCallState',
        'NORESPONSE_CodRecallType',
        'NORESPONSE_MinRecall',
        'BUSY_PublicRecall',
        'BUSY_CodCallState',
        'BUSY_CodRecallType',
        'BUSY_MinRecall',
        'CodZoneGroup',
        'CodLastUserEdit',
    ];

    // Cast dei tipi
    protected $casts = [
        'IDHeader' => 'int',
        'CodHeaderParent' => 'int',
        'Disabled' => 'boolean',
        'DateStart' => 'datetime',
        'DateStop' => 'datetime',
        'CodPortfolio' => 'int',
        'BindsToArea' => 'boolean',
        'UseTrigger' => 'boolean',
        'AllowPublicRecalls' => 'boolean',
        'NORESPONSE_Timeout' => 'int',
        'NORESPONSE_PublicRecall' => 'boolean',
        'NORESPONSE_CodCallState' => 'int',
        'NORESPONSE_CodRecallType' => 'int',
        'NORESPONSE_MinRecall' => 'int',
        'BUSY_PublicRecall' => 'boolean',
        'BUSY_CodCallState' => 'int',
        'BUSY_CodRecallType' => 'int',
        'BUSY_MinRecall' => 'int',
        'CodZoneGroup' => 'int',
        'TimestampIns' => 'datetime',
        'TimestampEdt' => 'datetime',
    ];

    // Relazione con il suo id primario
    public function parent()
    {
        return $this->belongsTo(self::class, 'CodHeaderParent', 'IDHeader');
    }

    // Relazione con la tabella Portfolios
    public function portfolio()
    {
        return $this->belongsTo(Portfolios::class, 'CodPortfolio', 'IDPortfolio');
    }

    // Relazione con la tabella Lists
    public function lists()
    {
        return $this->hasMany(Lists::class, 'CodHeader', 'IDHeader');
    }

    // Relazione con Calls_States per codice chiamate Non risponde
    public function callStateNoResponse()
    {
        return $this->belongsTo(Calls_States::class, 'NORESPONSE_CodCallState', 'IDCallState');
    }

    // Relazione con Calls_States per codice chiamate occupate
    public function callStateBusy()
    {
        return $this->belongsTo(Calls_States::class, 'BUSY_CodCallState', 'IDCallState');
    }

    // Relazione con Calls_States
    public function callsStates()
    {
        return $this->hasMany(Calls_States::class, 'CodHeader', 'IDHeader');
    }

    // Relazione con Calls_WhyNot
    public function callsWhyNot()
    {
        return $this->hasMany(Calls_WhyNot::class, 'CodHeader', 'IDHeader');
    }
}
