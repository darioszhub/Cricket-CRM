<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    // Nome della tabella
    protected $table = 'Groups';

    // Chiave primaria
    protected $primaryKey = 'IDUsersGroup';

    // Indica che la chiave primaria non è un numero incrementale
    public $incrementing = false;

    // Tipo della chiave primaria
    protected $keyType = 'string';

    // Disabilita i timestamps gestiti automaticamente da Laravel
    public $timestamps = false;

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    // Specifica i campi della tabella che possono essere modificati
    protected $fillable = [
        'IDUsersGroup',
        'Description',
        'TimestampEDT',
        'CodUserLastEdit',
    ];

    // Cast dei campi per il tipo corretto
    protected $casts = [
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    //Relazione con la tabella Groups_Headers 
    public function headers()
    {
        return $this->hasMany(Groups_Headers::class, 'CodUsersGroup', 'IDUsersGroup');
    }

    //Relazione con la tabella Groups_Parameters.
    public function groupsParameters()
    {
        return $this->hasMany(Groups_Parameters::class, 'CodUsersGroup', 'IDUsersGroup');
    }

    //Relazione con la tabella Groups_OStates
    public function groupsOStates()
    {
        return $this->hasMany(Groups_OStates::class, 'CodUsersGroup', 'IDUsersGroup');
    }

    //Relazione con la tabella Groups_Users
    public function groupUsers()
    {
        return $this->hasMany(Groups_Users::class, 'CodUsersGroup', 'IDUsersGroup');
    }
}
