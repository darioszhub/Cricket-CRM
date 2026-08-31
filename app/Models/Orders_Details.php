<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_Details extends Model
{
    // Nome della tabella
    protected $table = 'Orders_Details';

    // Chiave primaria
    protected $primaryKey = 'IDDetail';

    // Disabilita i timestamp automatici se non usati
    public $timestamps = false;

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    // Colonne assegnabili in massa
    protected $fillable = [
        'CodOrder',
        'CodPS',
        'Description',
        'UM',
        'UnitCost',
        'UnitPrice',
        'VAT',
        'RevenueAGN',
        'RevenueTLK',
        'Quantity',
        'State1',
        'State2',
        'State3',
        'State4',
        'State5',
        'Date_Confirm',
        'Date_Cancel',
        'Date_Instal',
        'FreeDate1HEADER',
        'FreeDate1',
        'FreeDate2HEADER',
        'FreeDate2',
        'FreeDate3HEADER',
        'FreeDate3',
        'FreeDate4HEADER',
        'FreeDate4',
        'FreeDate5HEADER',
        'FreeDate5',
        'FreeField1HEADER',
        'FreeField1',
        'FreeField2HEADER',
        'FreeField2',
        'FreeField3HEADER',
        'FreeField3',
        'FreeField4HEADER',
        'FreeField4',
        'FreeField5HEADER',
        'FreeField5',
        'TimestampINS',
        'TimestampEDT',
        'CodUserLastEdit'
    ];

    // Cast delle date
    protected $casts = [
        'UnitCost' => 'decimal:2',       // Campi monetari
        'UnitPrice' => 'decimal:2',      // Campi monetari
        'RevenueAGN' => 'decimal:2',     // Campi monetari
        'RevenueTLK' => 'decimal:2',     // Campi monetari
        'Quantity' => 'float',           // Campo float
        'Date_Confirm' => 'datetime',
        'Date_Cancel' => 'datetime',
        'Date_Instal' => 'datetime',
        'FreeDate1' => 'datetime',
        'FreeDate2' => 'datetime',
        'FreeDate3' => 'datetime',
        'FreeDate4' => 'datetime',
        'FreeDate5' => 'datetime',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
    ];

    // Accessor per campi monetari
    public function getUnitCostAttribute($value)
    {
        return number_format($value, 2, '.', ','); // Formattazione: "1,234.56"
    }

    public function getUnitPriceAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function getRevenueAGNAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function getRevenueTLKAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    // Mutator per campi monetari (arrotondamento)
    public function setUnitCostAttribute($value)
    {
        $this->attributes['UnitCost'] = round($value, 2);
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['UnitPrice'] = round($value, 2);
    }

    public function setRevenueAGNAttribute($value)
    {
        $this->attributes['RevenueAGN'] = round($value, 2);
    }

    public function setRevenueTLKAttribute($value)
    {
        $this->attributes['RevenueTLK'] = round($value, 2);
    }

    // Relazioni
    public function order()
    {
        return $this->belongsTo(Orders::class, 'CodOrder', 'IDOrder');
    }

    public function ordersPs()
    {
        return $this->belongsTo(Orders_PS::class, 'CodPS', 'IDPS');
    }
}
