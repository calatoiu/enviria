<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Components\RODate;
use Illuminate\Support\Facades\DB;

class Autorizatie extends Model
{
    use HasFactory;
  protected $fillable = [
    'AutorizatieID',
    'CIF',
    'PunctLucruID',
    'Nr',
    'Data',
    'Nota',
    'NrRevizuire',
    'DataRevizuire',
    'NrDecVal',
    'DataDecVal',
  ];
    protected $table = 'autorizatie';
    protected $primaryKey = 'AutorizatieID';
    protected $keyType = 'string';
    public $timestamps = false;

    public function punctlucru()
    {
        return $this->belongsTo(Punctlucru::class, 'PunctLucruID', 'PunctLucruID');
    }
}
