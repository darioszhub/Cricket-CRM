<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_DetailStates extends Model
{
    // Nome della tabella
    protected $table = 'Orders_DetailStates';

    // Chiave primaria
    protected $primaryKey = 'State';

    // La chiave primaria non è un intero auto-incrementale
    public $incrementing = false;

    // La chiave primaria è un varchar
    protected $keyType = 'string';

    // Disabilita la gestione automatica di created_at
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Gestisce le modifiche sulla colonna TimestampEDT
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'State',
        'DTYPE',
        'Visible4Selling',
        'Group',
        'FreeDate1HEADER',
        'FreeDate2HEADER',
        'FreeDate3HEADER',
        'FreeDate4HEADER',
        'FreeDate5HEADER',
        'FreeField1HEADER',
        'FreeField2HEADER',
        'FreeField3HEADER',
        'FreeField4HEADER',
        'FreeField5HEADER',
        'Disabled',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    protected $casts = [
        'Visible4Selling' => 'boolean',
        'Disabled' => 'boolean',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    //Relazione con la tabella Orders_States
    public function ordersStates()
    {
        return $this->hasMany(Orders_States::class, 'CodDetailState', 'State');
    }
}
