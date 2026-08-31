<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_States extends Model
{
    // Nome della tabella
    protected $table = 'Orders_States';

    // Chiave primaria
    protected $primaryKey = 'IDOrderState';

    // Disabilita la gestione automatica di Laravel per i timestamps
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Gestisce le modifiche sulla colonna TimestampEDT
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'Description',
        'OTYPE',
        'CodListState',
        'PublicRecall',
        'CodRecallType',
        'MinAutoRecall',
        'Group',
        'CodDetailState',
        'ForceNewOrder',
        'FreeField1',
        'FreeField2',
        'FreeField3',
        'FreeField4',
        'FreeField5',
        'Disabled',
        'OrderFHeader1',
        'OrderFHeader2',
        'OrderFHeader3',
        'OrderFHeader4',
        'OrderFHeader5',
        'OrderDHeader1',
        'OrderDHeader2',
        'OrderDHeader3',
        'OrderDHeader4',
        'OrderDHeader5',
        'CodUserLastEdit',
    ];

    protected $casts = [
        'IDOrderState' => 'integer',
        'CodListState' => 'integer',
        'PublicRecall' => 'boolean',
        'CodRecallType' => 'integer',
        'MinAutoRecall' => 'integer',
        'ForceNewOrder' => 'boolean',
        'Disabled' => 'boolean',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];


    // Relazione con il modello Orders_DetailStates
    public function detailState()
    {
        return $this->belongsTo(Orders_DetailStates::class, 'CodDetailState', 'State');
    }

    //Relazione con il modello Orders
    public function orders()
    {
        return $this->hasMany(Orders::class, 'OrderState', 'IDOrderState');
    }

    //Relazione con il modello Groups_OStates
    public function groupsOStates()
    {
        return $this->hasMany(Groups_OStates::class, 'CodOrderState', 'IDOrderState');
    }

    //Relazione con il modello Calls_States
    public function callsStates()
    {
        return $this->hasMany(Calls_States::class, 'CodOrderState', 'IDOrderState');
    }
}
