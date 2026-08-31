<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_PS extends Model
{
    // Specifica la tabella
    protected $table = 'Orders_PS';

    // Chiave primaria
    protected $primaryKey = 'IDPS';

    // Disabilita l'incremento automatico poiché la chiave primaria è un varchar
    public $incrementing = false;

    // Specifica il tipo di chiave primaria
    protected $keyType = 'string';

    // Disabilita i timestamp automatici se non usati
    public $timestamps = false;

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    // Specifica i campi della tabella che possono essere modificati
    protected $fillable = [
        'Description',
        'CodPortfolio',
        'Disabled',
        'Group1',
        'Group2',
        'Group3',
        'Group4',
        'Visible4Selling',
        'UM',
        'UnitCost',
        'UnitPrice',
        'VAT',
        'RevenueAGN',
        'RevenueTLK',
        'MinQuantity',
        'TimestampINS',
        'TimestampEDT',
        'CodUserLastEdit',
        'RevenueAGN_Attivo',
        'RevenueTLK_Attivo',
    ];

    // Cast dei campi per il tipo corretto
    protected $casts = [
        'CodPortfolio' => 'boolean',
        'Disabled' => 'boolean',
        'Visible4Selling' => 'boolean',
        'UnitCost' => 'float',
        'UnitPrice' => 'float',
        'RevenueAGN' => 'float',
        'RevenueTLK' => 'float',
        'RevenueAGN_Attivo' => 'float',
        'RevenueTLK_Attivo' => 'float',
        'MinQuantity' => 'float',
        'VAT' => 'integer',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Relazioni
    public function product_details()
    {
        return $this->hasMany(Orders_Details::class, 'CodPS', 'IDPS');
    }
}
