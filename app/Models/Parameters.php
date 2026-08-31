<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameters extends Model
{
    // Nome della tabella
    protected $table = 'Parameters';

    // Chiave primaria
    protected $primaryKey = 'Parameter';

    // Disabilita auto-incremento (char come chiave primaria)
    public $incrementing = false;

    // La chiave primaria è un char
    protected $keyType = 'string';

    // Disabilita i timestamps automatici
    public $timestamps = false;

    protected $fillable = [
        'Parameter',
        'Description',
        'Choices',
        'Value',
    ];

    //Relazione con la tabella Groups_Parameters.
    public function groupsParameters()
    {
        return $this->hasMany(Groups_Parameters::class, 'CodParameter', 'Parameter');
    }
}
