<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls_States extends Model
{
    // Nome della tabella
    protected $table = 'Calls_States';

    // Chiave primaria
    protected $primaryKey = 'IDCallState';

    // La chiave primaria non è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Gestisce le modifiche sulla colonna TimestampEDT
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'Description',
        'Group',
        'CallType',
        'USEFUL',
        'CodOrderState',
        'CodListState',
        'PublicRecall',
        'CodRecallType',
        'MinAutoRecall',
        'CodHeader',
        'Disabled',
        'FreeField1',
        'FreeField2',
        'FreeField3',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    protected $casts = [
        'IDCallState' => 'int',
        'USEFUL' => 'boolean',
        'CodOrderState' => 'int',
        'CodListState' => 'int',
        'PublicRecall' => 'boolean',
        'CodRecallType' => 'int',
        'MinAutoRecall' => 'int',
        'CodHeader' => 'int',
        'Disabled' => 'boolean',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Relazione con Orders_States
    public function orderState()
    {
        return $this->belongsTo(Orders_States::class, 'CodOrderState', 'IDOrderState');
    }

    // Relazione con Lists_States
    public function listState()
    {
        return $this->belongsTo(Lists_States::class, 'CodListState', 'IDListState');
    }

    // Relazione con Lists_RecallTypes
    public function recallType()
    {
        return $this->belongsTo(Lists_RecallTypes::class, 'CodRecallType', 'IDRecallType');
    }

    // Relazione con Lists_Headers
    public function header()
    {
        return $this->belongsTo(Lists_Headers::class, 'CodHeader', 'IDHeader');
    }
}
