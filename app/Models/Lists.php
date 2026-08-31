<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    // Nome della tabella
    protected $table = 'Lists';

    // Chiave primaria
    protected $primaryKey = 'IDList';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Disabilita la gestione dei timestamps di Laravel
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampIns';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEdt';

    // Campi fillable
    protected $fillable = [
        'CodCustomer',
        'CodHeader',
        'CodListState',
        'CodAgentT',
        'CodRecallType',
        'RecallDateTime',
        'NoteEXT',
        'NoteINT',
        'LastContact',
        'CalledTimes',
        'TimestampEdt',
        'CodLastUserEdit',
    ];

    // Cast dei campi
    protected $casts = [
        'CodCustomer' => 'integer',
        'CodHeader' => 'integer',
        'CodListState' => 'integer',
        'CodAgentT' => 'integer',
        'CodRecallType' => 'integer',
        'RecallDateTime' => 'datetime',
        'LastContact' => 'datetime',
        'CalledTimes' => 'integer',
        'TimestampIns' => 'datetime',
        'TimestampEdt' => 'datetime',
    ];

    // Relazione con Customers
    /*   public function customer()
    {
        return $this->belongsTo(Customers::class, 'CodCustomer', 'IDCustomer');
    }
 */
    // Relazione con Lists_Headers
    public function header()
    {
        return $this->belongsTo(Lists_Headers::class, 'CodHeader', 'IDHeader');
    }

    // Relazione con Lists_States
    public function state()
    {
        return $this->belongsTo(Lists_States::class, 'CodListState', 'IDListState');
    }

    // Relazione con Agents
    public function agent()
    {
        return $this->belongsTo(Agents::class, 'CodAgentT', 'IDAgent');
    }

    // Relazione con Lists_RecallTypes
    public function recallType()
    {
        return $this->belongsTo(Lists_RecallTypes::class, 'CodRecallType', 'IDRecallType');
    }

    // Relazione con Calls
    public function calls()
    {
        return $this->hasMany(Calls::class, 'CodList', 'IDList');
    }
}
