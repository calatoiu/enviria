<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Components\RODate;
use Illuminate\Support\Facades\DB;

class Punctlucru extends Model
{
    use HasFactory;
  protected $fillable = [
    'PunctLucruID',
    'Pass',
    'CIF',
    'Denumire',
    'DateContact',
    'Localitate',
    'Adresa',
    'Telefon',
    'Email',
    'CAEN',
    'NrSalariati',
    'NrAM',
    'DataAM',
    'DataRevAM',
    'NotaAM',
];
    protected $table = 'punctlucru';
    protected $primaryKey = 'PunctLucruID';
    protected $keyType = 'string';
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'CIF', 'CIF');
    }
}
