<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Components\RODate;
use Illuminate\Support\Facades\DB;

class Factura extends Model
{
    use HasFactory;
  protected $fillable = [
    'SerieNumar',
    'Data',
    'CUI',
    'Furnizor',
    'LunaIni',
    'LunaFin',
    'Valoare',
    'Continut',
    'Nota',
    'FaraContract',
    'NotaText',
];
    protected $table = 'factura';
    protected $primaryKey = 'SerieNumar';
    protected $keyType = 'string';
    public $timestamps = false;

    public function furnizor()
    {
        return $this->belongsTo(Furnizor::class, 'Furnizor', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'CUI', 'CIF');
    }

    public function getIntervalAttribute()
    {
        return RODate::getIntervalLuni($this->LunaIni, $this->LunaFin);
    }

    public function decontari()
    {
        return $this->hasMany(Decontare::class, 'SerieNumarFactura', 'SerieNumar');
    }

    public function getSoldAttribute()
    {
        $decontari = $this->decontari();
        $nrdec = $decontari->get()->count();
        $docSelect = $decontari->selectRaw('decontare.SerieNumarFactura, SUM(decontare.Suma) as decontat')
                                ->groupBy('decontare.SerieNumarFactura');
     //   dd($decSelect);
     //   DB::enableQueryLog();
        $sumdec = $docSelect->first();
      //  dd(DB::getQueryLog(), $sumdec, $nrdec);
     //   dd($sumdec);

        $decontat =  $sumdec ?  $sumdec->decontat : 0;
        return $this->Valoare - $decontat;

        // $decontat =  $this->decontari() ?
        // $this->decontari()
        //     ->selectRaw('decontare.SerieNumarFactura, SUM(decontare.Suma) as decontat')->groupBy('decontare.SerieNumarFactura')->first()->decontat : 0;
        // return
        // $this->Valoare - $decontat;
    }


    public function incasari()
    {
        return $this->belongsToMany(Incasare::class, 'decontare', 'SerieNumarFactura', 'ReferintaIncasare');
    }


}
