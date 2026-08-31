<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    // Definisci la tabella associata
    protected $table = 'Orders';

    // La chiave primaria
    protected $primaryKey = 'IDOrder';

    // Disabilita timestamps (se non hai campi "created_at")
    public $timestamps = false; // La tabella non segue i timestamp automatici di Laravel

    // Campo per aggiornare la data di modifica
    const UPDATED_AT = 'TimestampEDT';

    protected $fillable = [
        'CodCustomer',
        'CodAgentT',
        'CodAgentE',
        'CodOrder',
        'TimestampINS',
        'OrderState',
        'CodPortfolio',
        'NegotiationTarget',
        'NotesRIF',
        'NotesINT',
        'NotesEXT',
        'NotesDemand',
        'NotesCompetitors',
        'NotesRecall',
        'RecallDateTime',
        'Date_Appnt',
        'Date_Contract',
        'Date_ContractIns',
        'Date_Confirm',
        'SENT',
        'InstallationDate',
        'ClosingPeriod',
        'Quality',
        'QualityDate',
        'Consents',
        'DiscountPerc',
        'WhyNot1',
        'WhyNot2',
        'Name',
        'Surname',
        'Gender',
        'CodFisc',
        'VAT',
        'Tel1',
        'Tel2',
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
        'ReferentName',
        'ReferentSurname',
        'ReferentRole',
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
        'TimestampEDT',
        'CodLastUserEdit',
    ];

    // Cast dei campi data
    protected $casts = [
        'TimestampINS' => 'datetime',
        'RecallDateTime' => 'datetime',
        'Date_Appnt' => 'datetime',
        'Date_Contract' => 'datetime',
        'Date_ContractIns' => 'datetime',
        'Date_Confirm' => 'datetime',
        'InstallationDate' => 'datetime',
        'QualityDate' => 'datetime',
        'FreeDate1' => 'datetime',
        'FreeDate2' => 'datetime',
        'FreeDate3' => 'datetime',
        'FreeDate4' => 'datetime',
        'FreeDate5' => 'datetime',
        'TimestampEDT' => 'datetime',
        'DocReleaseDate' => 'datetime',
        'DocExpirationDate' => 'datetime',
    ];

    // Relazione con Orders_Details
    public function orders_details()
    {
        return $this->hasMany(Orders_Details::class, 'CodOrder', 'IDOrder');
    }

    // Relazione con Orders_States
    public function orderState()
    {
        return $this->belongsTo(Orders_States::class, 'OrderState', 'IDOrderState');
    }

    // Relazione con Calls
    public function calls()
    {
        return $this->hasMany(Calls::class, 'CodOrder', 'IDOrder');
    }
}
