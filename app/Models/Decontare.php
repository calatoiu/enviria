<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decontare extends Model
{
  protected $fillable = [
    'id',
    'SerieNumarFactura',
    'ReferintaIncasare',
    'Suma',
];
    protected $table = 'decontare';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'SerieNumarFactura', 'SerieNumar');
    }

    public function incasare()
    {
        return $this->belongsTo(Incasare::class, 'ReferintaIncasare', 'Referinta');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'CUI', 'CIF');
    }


}
