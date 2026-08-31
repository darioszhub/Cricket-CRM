<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolios extends Model
{
    // Nome della tabella
    protected $table = 'Portfolios';

    // Chiave primaria
    protected $primaryKey = 'IDPortfolio';

    // La primary key è incrementale
    public $incrementing = true;

    // La chiave primaria è un intero
    protected $keyType = 'int';

    // Disabilita timestamps (non gestiti da laravel)
    public $timestamps = false;

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'Portfolio',
        'CodPortfolioParent',
        'Disabled',
        'COLOR',
        'TimestampEDT',
        'CodLastUserEdit',
        'Group',
        'Group_2',
    ];

    protected $casts = [
        'IDPortfolio' => 'integer',
        'CodPortfolioParent' => 'integer',
        'Disabled' => 'boolean',
        'COLOR' => 'integer',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    //Relazione per ottenere il portfolio padre.
    public function parentPortfolio()
    {
        return $this->belongsTo(Portfolios::class, 'CodPortfolioParent', 'IDPortfolio');
    }

    //Relazione per ottenere i sotto-portafogli di un portfolio.
    public function childPortfolios()
    {
        return $this->hasMany(Portfolios::class, 'CodPortfolioParent', 'IDPortfolio');
    }

    //Relazione con la tabella Lists_Headers
    public function headers()
    {
        return $this->hasMany(Lists_Headers::class, 'CodPortfolio', 'IDPortfolio');
    }

    //Relazione con la tabella Agents_Assignments
    public function agents_assignments()
    {
        return $this->hasMany(Agents_Assignments::class, 'CodPortfolio', 'IDPortfolio');
    }

    //Relazione con la tabella Customers
    public function customers()
    {
        return $this->hasMany(Customers::class, 'CodPortfolio', 'IDPortfolio');
    }

    //Relazione con la tabella Customers come Portafoglio precedente
    public function previousCustomers()
    {
        return $this->hasMany(Customers::class, 'CodPreviousPortfolio', 'IDPortfolio');
    }
}
