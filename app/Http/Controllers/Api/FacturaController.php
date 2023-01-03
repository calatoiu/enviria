<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\FacturaRequest;
use App\Http\Resources\FacturaResource;
use App\Models\Factura;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Mpdf;

class FacturaController extends Controller
{
	public function index()
	{
		return FacturaResource::collection(Factura::all());
	}

	public function indexcui($CUI)
	{
        $client = Client::with('facturi')->find($CUI);
        return FacturaResource::collection($client->facturi()->get()->sortByDesc('Data'));
	}

	public function textfactura()
	{
        return (new \App\Settings\GeneralSettings)->text_factura;
	}




	public function store(FacturaRequest $request)
	{
		$factura = Factura::create($request->validated());
		return new FacturaResource($factura);
	}

	public function show(Factura $factura)
	{
		return new FacturaResource($factura);
	}

	public function showw($SerieNumar)
	{
        $factura = Factura::whereSerienumar($SerieNumar)->first();
//dd($factura);

 //       if($factura)
		    return new FacturaResource($factura);
 //       else
  //         return '{}'; //response()->noContent();
	}

    public function newfactura($CUI)
    {
        $factura = new Factura();
        $client = Client::find($CUI);
        $serie = 'FF' . $client->furnizor->prefix . \Carbon\Carbon::now()->isoFormat('YY') . '-';

        $maxSerieNumar = Factura::where('SerieNumar','like', $serie. '%')->max('SerieNumar');
        $nextSerieNumar = $serie . '0001';
        if ($maxSerieNumar)
        {
            $nr = intval(substr($maxSerieNumar, 7, 4));
            $nextSerieNumar = $serie . sprintf('%04d', $nr + 1);
        }
        $factura->SerieNumar = $nextSerieNumar;
        $factura->Data = \Carbon\Carbon::now()->isoFormat('YYYY-MM-DD');
        $factura->CUI = $CUI;
        $factura->Furnizor = $client->Furnizor;

        $lunaIni = date('Ym', strtotime("last day of 0 month"));
        $lunaFin = $lunaIni;

        $maxLunaFin = Factura::where('CUI',$CUI)->max('LunaFin');
        if ($maxLunaFin) {
            $date = \DateTime::createFromFormat('Ymd', $maxLunaFin . '01');
            $date->modify("+1 month");
            $lunaIni = $date->format('Ym');
            if ($lunaIni > $lunaFin)
                $lunaFin = $lunaIni;
        }

        $factura->LunaIni = \Carbon\Carbon::now()->isoFormat('YYYYMM');
        $factura->LunaFin = \Carbon\Carbon::now()->isoFormat('YYYYMM');
        $factura->Valoare = $client->Valoare;
        $factura->Continut = '';
        $factura->Nota = '';
        $factura->FaraContract = 0;
        $factura->NotaText = 0;
        return new FacturaResource($factura);
    }


	public function update(FacturaRequest $request, Factura $factura)
	{
		$factura->update($request->validated());
		return new FacturaResource($factura);
	}

	public function updateserienumar(FacturaRequest $request, $SerieNumar)
	{
        $factura = Factura::whereSerienumar($SerieNumar)->first();
        $rv = $request->validated();
		$factura->update($rv);
		return new FacturaResource($factura);
	}

	public function destroy(Factura $factura)
	{
		$factura->delete();
		return response()->noContent();
	}



//====================
// PDF
//====================
    protected function html2pdf($html, $fileName)
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',           // format - A4, for example, default ''
            'default_font_size' => 0,   // font size - default 0
            'default_font' => '',       // default font family
            'margin_left' => 20,    	// 15 margin_left
            'margin_right' => 15,    	// 15 margin right
            'margin_top' => 15,    	    // 15 margin right
            'margin_bottom' => 25,    	// 15 margin right
            'margin_header' => 10,      // 9 margin header
            'margin_footer' => 10,      // 9 margin footer
            'orientation' => 'P'  	    // L - landscape, P - portrait
        ]);
        $mpdf->allow_charset_conversion=false;  // Set by default to TRUE
        $mpdf->SetProtection(['print','copy']);
        $mpdf->SetTitle("Factura mediu");
        $mpdf->SetAuthor("");
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoLangToFont = true;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($fileName,'D');
    }

    public function facturapdf($SerieNumar)
    {
		$factura = Factura::find($SerieNumar);
        try {
            $html = gzinflate($factura->Continut);
        } catch (\Throwable $th) {
             $html = $this->buildfacturahtml($factura);
        }

        $denumire =  preg_replace("/[^a-z0-9\_\-\.]/i", '_',  $factura->client->Denumire);
        $fileName =
            $factura->SerieNumar . '-' .
            $factura->Data . '-' .
            $denumire  . '.pdf';
        $this->html2pdf($html, $fileName);
    }

    public function facturahtml($SerieNumar)
    {
		$factura = Factura::find($SerieNumar);
        try {
            $html = gzinflate($factura->Continut);
        } catch (\Throwable $th) {
             $html = $this->buildfacturahtml($factura);
        }
        return $html;
    }

	public function buildfacturahtml(Factura $factura)
	{
        $html = file_get_contents(__DIR__ . '/templates/FacturaTemplate.html');

        $textServicii = 'Servicii pentru protecția mediului';

        if ($factura->LunaIni != ''){
            $textServicii .= ', ' . \App\Components\RODate::getTextLuni($factura->LunaIni, $factura->LunaFin);}

        if ($factura->client->NrContract != '' && !$factura->FaraContract) {
            $textServicii .= ', conform contract nr.' . $factura->client->NrContract . '/' .
                date('d.m.Y', strtotime($factura->client->DataContract));// 'dd.MM.yyyy'
            }

        if ($factura->NotaText) {
            $textServicii = $factura->Nota;}

        $facturineachitate = $factura->client->facturineachitate;
        $htmlFacturiNeachitate = '';
        if ($facturineachitate->count() > 0)
        {
            $total = 0;
            foreach($facturineachitate as $factsold) {
                if ($factsold->Data < $factura->Data ) {
                    $total += $factsold->Sold;
                }
            }
            if ($total) {
                $htmlFacturiNeachitate = '
                    <hr>
                    <p>Facturi neachitate la data prezentei</p>
                    <style type="text/css">
                        .neachitateTable { border-collapse:collapse;color:#000 }
                        .neachitateTable td { padding:5px; border:0;border-bottom:1px dotted #BDB76B; }
                    </style>
                    <table class="neachitateTable">
                    ';
                foreach($facturineachitate as $factsold) {
                    if ($factsold->Data < $factura->Data ) {
                        $htmlFacturiNeachitate .= '
                            <tr>
                                <td>' . $factsold->SerieNumar . '</td>
                                <td>' . $factsold->Data . '</td>
                                <td align=right>' . $factsold->Sold . ' lei</td>
                            </tr>';
                    }
                }

                $htmlFacturiNeachitate .= '
                        <tr>
                            <td colspan="2" align=right>Total neachitat</td>
                            <td align=right>
                                <b>' . $total . ' lei</b>
                            </td>
                        </tr>
                    </table>
                ';
            }
        }

        $replace =
        [
            '{background color}' => $factura->furnizor->backgroundcolor,
            // '{eticheta site}' => 'Site',
            // '{site}' => '<a href="http://www.mediuexpres.ro">www.mediuexpres.ro</a>',
            '{eticheta site}' => '',
            '{site}' => '',

            '{factura serie}' => explode('-', $factura->SerieNumar)[0],
            '{factura numar}' => explode('-', $factura->SerieNumar)[1],
            '{factura data}' => date('d.m.Y', strtotime($factura->Data)),

            '{furnizor nume}'    => $factura->furnizor->Denumire,
            '{furnizor nr orc}'  => $factura->furnizor->NrRegCom,
            '{furnizor CUI}'     => $factura->furnizor->CUI,
            '{furnizor sediul}'  => $factura->furnizor->Sediu,
            '{furnizor judetul}' => $factura->furnizor->Judet,
            '{furnizor contul}'  => $factura->furnizor->ContBancar,
            '{furnizor banca}'   => $factura->furnizor->Banca,

            '{cumparator nume}'    => $factura->client->Denumire,
            '{cumparator nr orc}'  => $factura->client->NrRegCom,
            '{cumparator CUI}'     => $factura->client->RO . $factura->client->CIF,
            '{cumparator sediul}'  => $factura->client->Sediu,
            '{cumparator judetul}' => $factura->client->Judet,
            '{cumparator contul}'  => $factura->client->ContBancar,
            '{cumparator banca}'   => $factura->client->Banca,

            '{text servicii}'    => $textServicii,
            '{valoare servicii}' => $factura->Valoare,
            '{facturi neachitate}' => $htmlFacturiNeachitate,
        ];

        $html = strtr($html, $replace);
        return $html;

	}

//====================






    public function incasare($SerieNumarFactura, $ReferintaIncasare, $Suma)
    {
        $factura = Factura::find($SerieNumarFactura);
        $factura->incasari()->attach($ReferintaIncasare, ['Suma' => $Suma]);
    }



}
