<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incasare extends Model
{
  protected $fillable = [
    'Data',
    'Tip',
    'Suma',
    'Client',
    'Cont',
    'Detalii',
    'Referinta',
    'CUI',
    'Furnizor',
    'Activ',
];
    protected $table = 'incasare';
    protected $primaryKey = 'Referinta';
    protected $keyType = 'string';
    public $timestamps = false;

    public function furnizor()
    {
        return $this->belongsTo(Furnizor::class, 'Furnizor', 'id')->first();
    }

    public function client()
    {
//        return $this->belongsTo(Client::class, 'CUI', 'CIF')->first();
        return $this->belongsTo(Client::class, 'CUI', 'CIF');
    }

    public function decontari()
    {
        return $this->hasMany(Decontare::class, 'ReferintaIncasare', 'Referinta');
    }



    public function facturi()
    {
        return $this->belongsToMany(Factura::class, 'decontare', 'ReferintaIncasare', 'SerieNumarFactura')->withPivot('Suma');

    }
// deconteaza o facura
// $incas->facturi()->attach('FFZC22-0010', ['Suma' => '9999']);

}
