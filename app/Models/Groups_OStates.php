<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups_OStates extends Model
{
    // Nome della tabella
    protected $table = 'Groups_OStates';

    // Disabilita timestamps automatici
    public $timestamps = false;

    // Indica a Laravel che non esiste una chiave primaria
    protected $primaryKey = null;

    // La tabella non ha chiavi primarie
    public $incrementing = false;

    // Campi modificabili tramite assegnazione di massa
    protected $fillable = [
        'CodUsersGroup',
        'CodOrderState',
        'CanSave',
        'CanDelete',
        'EditState',
        'EditCustomer',
        'EditPortfolio',
        'EditAppto',
        'EditTLK',
        'EditEXT',
        'EditNoteINT',
        'EditNoteEXT',
    ];

    protected $casts = [
        'CodOrderState' => 'integer',
        'CanSave' => 'boolean',
        'CanDelete' => 'boolean',
        'EditState' => 'boolean',
        'EditCustomer' => 'boolean',
        'EditPortfolio' => 'boolean',
        'EditAppto' => 'boolean',
        'EditTLK' => 'boolean',
        'EditEXT' => 'boolean',
        'EditNoteINT' => 'boolean',
        'EditNoteEXT' => 'boolean',
    ];


    //Relazione con il modello Groups.
    public function group()
    {
        return $this->belongsTo(Groups::class, 'CodUsersGroup', 'IDUsersGroup');
    }

    //Relazione con il modello Orders_States.
    public function orderState()
    {
        return $this->belongsTo(Orders_States::class, 'CodOrderState', 'IDOrderState');
    }
}
