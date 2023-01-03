<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;
  protected $fillable = [
    'CIF',
    'Denumire',
    'ContBancar',
    'Banca',
    'Sediu',
    'Judet',
    'NrRegCom',
    'RO',
    'NrContract',
    'DataContract',
    'Valoare',
    'NrAutorizatie',
    'DataAutorizatie',
    'DataExpirareAutorizatie',
    'Furnizor',
    'Note',
];
    protected $table = 'client';
    protected $primaryKey = 'CIF';
    public $timestamps = false;

    public function furnizor()
    {
        return $this->belongsTo(Furnizor::class, 'Furnizor', 'id');
    }

    public function facturi()
    {
        return $this->hasMany(Factura::class, 'CUI', 'CIF')
            ->orderBy('SerieNumar', 'DESC')
            ->select(['SerieNumar', 'Data', 'Valoare', 'LunaIni', 'LunaFin'])->addSelect(
                ['Sold' => function ($query) {
                    $query->selectRaw('factura.Valoare - ifnull(SUM(decontare.Suma),0)')
                        ->from('decontare')
                        ->whereColumn('decontare.SerieNumarFactura', 'factura.SerieNumar');
                }]
            );
    }

    public function punctelucru()
    {
        return $this->hasMany(Punctlucru::class, 'CIF', 'CIF')
            ->orderBy('PunctLucruID', 'DESC')
            ->select(['PunctLucruID', 'Denumire', 'NrAM', 'DataAM', 'DataRevAM', 'NotaAM']);
    }

    public function facturineachitate()
    {
        return $this->hasMany(Factura::class, 'CUI', 'CIF')
            ->whereRaw ("
                (factura.Valoare - ifnull((select sum(decontare.Suma)
                from decontare where decontare.SerieNumarFactura = factura.SerieNumar) , 0)) <> 0")
            ->orderBy('SerieNumar', 'ASC')
            ->select(['SerieNumar', 'Data', 'Furnizor', 'Valoare', 'LunaIni', 'LunaFin'])
            ->addSelect([
                DB::raw("
                    (factura.Valoare - ifnull(
                        (select sum(decontare.Suma)
                        from decontare where decontare.SerieNumarFactura = factura.SerieNumar) , 0)) as Sold")
                ])
            ;
    }

}
