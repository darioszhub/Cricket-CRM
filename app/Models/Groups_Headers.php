<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups_Headers extends Model
{
    // Nome della tabella
    protected $table = 'Groups_Headers';

    // Indica a Laravel che non esiste una chiave primaria
    protected $primaryKey = null;

    // Disabilita l'auto-incremento (non c'è una chiave primaria)
    public $incrementing = false;

    // Disabilita i timestamps automatici
    public $timestamps = false;

    protected $fillable = [
        'CodHeader',
        'CodUsersGroup',
        'CodUserLastEdit',
    ];

    protected $casts = [
        'TimestampINS' => 'datetime',
    ];

    //Relazione con la tabella Groups.
    public function group()
    {
        return $this->belongsTo(Groups::class, 'CodUsersGroup', 'IDUsersGroup');
    }

    /**
     * Relazione con la tabella Lists_Headers.
     */
    /* public function listHeader()
    {
        return $this->belongsTo(Lists_Headers::class, 'CodHeader', 'CodHeader');
    } */
}
