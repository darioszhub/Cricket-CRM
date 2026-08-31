<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups_Users extends Model
{
    // Nome della tabella
    protected $table = 'Groups_Users';

    // Indica che non c'è una chiave primaria singola
    protected $primaryKey = null;

    // Nessun incremento automatico per chiavi primarie composite
    public $incrementing = false;

    // Disabilita timestamps (non gestiti da laravel)
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Specifica il campo per gestire manualmente il timestamp delle modifiche
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'CodUsersGroup',
        'CodUser',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    protected $casts = [
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Relazione con la tabella Groups
    public function group()
    {
        return $this->belongsTo(Groups::class, 'CodUsersGroup', 'IDUsersGroup');
    }

    // Relazione con la tabella Users
    public function user()
    {
        return $this->belongsTo(Users::class, 'CodUser', 'IDUser');
    }
}
