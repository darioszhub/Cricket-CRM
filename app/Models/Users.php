<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    // Nome della tabella
    protected $table = 'Users';

    // La chiave primaria (se diversa da 'id')
    protected $primaryKey = 'IDUser';

    // Tipo della chiave primaria
    protected $keyType = 'string';  // La chiave primaria è una stringa

    // Disabilita timestamps (non gestiti da laravel)
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    // Campi che possono essere assegnati in massa
    protected $fillable = [
        'IDUser',
        'Username',
        'Keyword',
        'CodAgent',
        'PowerUser',
        'Disabled',
        'DBSPID',
        'DBLOGINTIME',
        'IPADDRESS',
        'TimestampLastLogin',
        'LoginVisible',
        'ChangePwdFirstLogin',
        'TimestampINS',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    // Cast automatico dei campi a tipi specifici
    protected $casts = [
        'PowerUser' => 'boolean', // Converte 0/1 in false/true
        'Disabled' => 'boolean',
        'LoginVisible' => 'boolean',
        'ChangePwdFirstLogin' => 'boolean',
        'TimestampLastLogin' => 'datetime',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Campi nascosti (se non vuoi che vengano restituiti nelle query)
    protected $hidden = [
        'Keyword', // Nasconde la password
    ];

    //Sovrascrivo il campo usato per il login
    public function getAuthIdentifierName()
    {
        return 'Username';
    }

    // Dico a Laravel dove trovare la password
    public function getAuthPassword()
    {
        return $this->Keyword;
    }

    // Relazione con la tabella Groups_Users
    public function groupsUsers()
    {
        return $this->hasMany(Groups_Users::class, 'CodUser', 'IDUser');
    }
}
