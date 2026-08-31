<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups_Parameters extends Model
{
    // Nome della tabella
    protected $table = 'Groups_Parameters';

    // Indica a Laravel che non esiste una chiave primaria
    protected $primaryKey = null;

    // La tabella non ha chiavi primarie
    public $incrementing = false;

    // Disabilita i timestamps automatici
    public $timestamps = false;

    protected $fillable = [
        'CodUsersGroup',
        'CodParameter',
        'Value',
    ];

    //Relazione con la tabella Groups.
    public function group()
    {
        return $this->belongsTo(Groups::class, 'CodUsersGroup', 'IDUsersGroup');
    }

    //Relazione con la tabella Parameters.
    public function parameter()
    {
        return $this->belongsTo(Parameters::class, 'CodParameter', 'Parameter');
    }
}
