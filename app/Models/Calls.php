<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    // Nome della tabella
    protected $table = 'Calls';

    // Chiave primaria
    protected $primaryKey = 'IDCall';

    // La chiave primaria è autoincrementale
    public $incrementing = true;
    protected $keyType = 'int';

    // Timestamp personalizzati
    const CREATED_AT = null;
    const UPDATED_AT = null;

    // Disabilita i timestamp automatici di Laravel (se non necessari)
    public $timestamps = false;

    // Campi assegnabili in massa
    protected $fillable = [
        'CallStart',
        'Duration',
        'DurationType',
        'ConversationNotes',
        'CodCallState',
        'WhyNot',
        'CodAgent',
        'CodList',
        'CodOrder',
    ];

    // Cast automatico dei tipi
    protected $casts = [
        'IDCall' => 'int',
        'CallStart' => 'datetime',
        'Duration' => 'int',
        'CodCallState' => 'int',
        'CodAgent' => 'int',
        'CodList' => 'int',
        'CodOrder' => 'int',
    ];

    // Relazioni
    /* public function callState()
    {
        return $this->belongsTo(Calls_States::class, 'CodCallState', 'IDCallState');
    } */

    public function agent()
    {
        return $this->belongsTo(Agents::class, 'CodAgent', 'IDAgent');
    }

    public function list()
    {
        return $this->belongsTo(Lists::class, 'CodList', 'IDList');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'CodOrder', 'IDOrder');
    }
}
