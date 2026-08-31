<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agents extends Model
{
    // Definisci la tabella associata
    protected $table = 'Agents';

    // La chiave primaria
    protected $primaryKey = 'IDAgent';

    // Tipo della chiave primaria
    protected $keyType = 'string';  // La chiave primaria è una stringa

    // Disabilita timestamps (se non hai campi "created_at" e "updated_at")
    public $timestamps = false; // La tabella non segue i timestamp automatici di Laravel

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampIns';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEdt';


    // Definisci i campi che sono "mass assignable" (in ordine alfabetico)
    protected $fillable = [
        'AgentCode',
        'Address',
        'ATYPE',
        'BadgeNumber',
        'BornCity',
        'BornDate',
        'BornNation',
        'BornProv',
        'CAP',
        'City',
        'CodAgentParent',
        'Codfisc',
        'Contract',
        'DismissalDate',
        'DocType',
        'DocNumber',
        'DocRelease',
        'DocExpire',
        'DocProvider',
        'Paycheck',
        'IBAN',
        'Notes',
        'Disabled',
        'TimestampINS',
        'TimestampEDT',
        'CodUserLastEdit',
        'Email',
        'Fax',
        'Gender',
        'HiringDate',
        'Name',
        'Phone',
        'PIVA',
        'Prov',
        'Surname',
        'Town',
        'Cell'
    ];

    // Se desideri che il campo 'IDAgent' sia incrementale, rimuovi questa riga
    public $incrementing = false;

    // Gestione delle date (se questi campi sono di tipo datetime) $dates è considerato obsoleto e $casts è il metodo moderno e flessibile
    protected $casts = [
        'BornDate' => 'datetime',
        'HiringDate' => 'datetime',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
        'DismissalDate' => 'datetime',
    ];

    // Se hai bisogno di accedere a una relazione con un altro modello (ad esempio, CodAgentParent come FK)
    public function parentAgent()
    {
        return $this->belongsTo(Agents::class, 'CodAgentParent', 'IDAgent');
    }

    // Relazione con Lists
    public function lists()
    {
        return $this->hasMany(Lists::class, 'CodAgentT', 'IDAgent');
    }

    // Relazione con Agents_Assignments
    public function agents_assignments()
    {
        return $this->hasMany(Agents_Assignments::class, 'CodAgent', 'IDAgent');
    }
    // Relazione con Agents_Calendar
    public function calendarEntries()
    {
        return $this->hasMany(Agents_Calendar::class, 'CodAgent', 'IDAgent');
    }

    // Relazione con Calls
    public function calls()
    {
        return $this->hasMany(Calls::class, 'CodAgent', 'IDAgent');
    }
}
