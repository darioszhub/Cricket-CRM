<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    // Nome della tabella
    protected $table = 'Customers';

    // Chiave primaria
    protected $primaryKey = 'IDCustomer';

    // La chiave primaria non è autoincrementale
    public $incrementing = false;

    // Tipo della chiave primaria
    protected $keyType = 'int';

    // Indica che TimestampINS è la colonna per la data di creazione
    const CREATED_AT = 'TimestampINS';

    // Gestisce le modifiche sulla colonna TimestampEDT
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'CodZone',
        'CodPortfolio',
        'Name',
        'Surname',
        'Gender',
        'CodFisc',
        'VAT',
        'Pref1',
        'Tel1',
        'Pref2',
        'Tel2',
        'Pref3',
        'Tel3',
        'Cell1',
        'Cell2',
        'Cell3',
        'Fax1',
        'Fax2',
        'Fax3',
        'Email1',
        'Email2',
        'Email3',
        'Web1',
        'Web2',
        'AddressDescription',
        'AddressToponomy',
        'Address',
        'AddressNumber',
        'Town',
        'City',
        'Prov',
        'ZipCode',
        'Country',
        'AddressDescription2',
        'AddressToponomy2',
        'Address2',
        'AddressNumber2',
        'Town2',
        'City2',
        'Prov2',
        'ZipCode2',
        'Country2',
        'BornWhen',
        'BornCity',
        'BornProv',
        'BornCountry',
        'DocType',
        'DocNumber',
        'DocReleaseDate',
        'DocExpirationDate',
        'DocProvider',
        'CDM1',
        'CDM2',
        'Bank',
        'IBAN',
        'CC_Number',
        'CC_Name',
        'CC_ExpireMonth',
        'CC_ExpireYear',
        'CC_VerificationNumber',
        'CC_NameOwner',
        'ReferentSurname',
        'ReferentName',
        'ReferentRole',
        'DiscountThreshold',
        'DiscountPerc',
        'DiscountExpiration',
        'FreeFieldsDescription',
        'FreeField1',
        'FreeField2',
        'FreeField3',
        'FreeField4',
        'FreeField5',
        'Locked',
        'LockedDate',
        'LockedWhy',
        'CodLastUserEdit',
        'BUSINESS',
        'NTYPE',
        'CodPreviousPortfolio',
        'LastUpdate',
    ];

    protected $casts = [
        'CodPortfolio' => 'int',
        'CodPreviousPortfolio' => 'int',
        'CC_ExpireMonth' => 'int',
        'CC_ExpireYear' => 'int',
        'DiscountThreshold' => 'float',
        'DiscountPerc' => 'float',
        'DiscountExpiration' => 'datetime',
        'BornWhen' => 'datetime',
        'DocReleaseDate' => 'datetime',
        'DocExpirationDate' => 'datetime',
        'LockedDate' => 'datetime',
        'LastUpdate' => 'datetime',
        'TimestampINS' => 'datetime',
        'TimestampEDT' => 'datetime',
        'Locked' => 'boolean',
        'BUSINESS' => 'boolean',
    ];

    // Relazione con Portfolios
    public function portfolio()
    {
        return $this->belongsTo(Portfolios::class, 'CodPortfolio', 'IDPortfolio');
    }

    // Relazione con Portfolios come portafoglio precedente
    public function previousPortfolio()
    {
        return $this->belongsTo(Portfolios::class, 'CodPreviousPortfolio', 'IDPortfolio');
    }

    // Relazione con Zones_Headers
    public function zone()
    {
        return $this->belongsTo(Zones_Headers::class, 'CodZone', 'IDZone');
    }
}
