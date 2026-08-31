<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agents_Calendar extends Model
{
    // Nome tabella
    protected $table = 'Agents_Calendar';

    // Chiave primaria
    protected $primaryKey = 'IDCalendar';

    // La chiave primaria è autoincrementale
    public $incrementing = true;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Gestisce le modifiche sulla colonna TimestampEDT
    const UPDATED_AT = 'TimestampEDT';

    // Mass assignment
    protected $fillable = [
        'CodAgent',
        'DayOfTheWeek',
        'MaxAppointments',
        'TimeStart',
        'TimeStop',
        'ExcludeHours',
        'FiveToNextApp',
        'Disabled',
        'TimestampEDT',
        'CodUserLastEdit',
        'MinNextApp',
    ];

    // Cast dei tipi di dato
    protected $casts = [
        'IDCalendar'      => 'int',
        'CodAgent'        => 'int',
        'DayOfTheWeek'    => 'int',
        'MaxAppointments' => 'int',
        'TimeStart'       => 'datetime',
        'TimeStop'        => 'datetime',
        'Disabled'        => 'boolean',
        'TimestampINS'    => 'datetime',
        'TimestampEDT'    => 'datetime',
        'MinNextApp'      => 'int',
    ];

    // Relazione con Agents
    public function agent()
    {
        return $this->belongsTo(Agents::class, 'CodAgent', 'IDAgent');
    }
}
