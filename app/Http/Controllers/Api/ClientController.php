<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\Furnizor;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mpdf\Tag\Tr;

class ClientController extends Controller
{
	// public function index()
	// {
	// 	return ClientResource::collection(Client::all());
	// }
	public function store(ClientRequest $request)
	{
      //  Log::debug($request);
      //  dd($request);
		$client = Client::create($request->validated());
		return new ClientResource($client);
	}
	public function show(Client $client)
	{
		return new ClientResource($client);
	}
	//public function showw(ClientRequest $request, $CIF)
	public function showw(\Illuminate\Http\Request $request, $CIF)
	{
     //   $header = $request->header();
      //  Log::debug($header);
      //  $request = $_GET;
     //   dd($request);
     //   $header = $request->header('Authorization');
        $client = Client::whereCif($CIF)->first();
		return new ClientResource($client);
	}

    public function anaf($cif)
    {
     // $this->middleware('web');
    //  $session = app('session');
     //   $token = csrf_token();
      //  dd($token);
        $anaf = new \Itrack\Anaf\Client();
    //    $cif = $cif . '1';
        $anaf->addCif($cif, Carbon::now()->toDateString());

         $company = cache()->remember('client-' . $cif, 3600, function () use ($anaf, $cif) {
            Log::debug('get cif from db - ' . $cif);
            try {
                return $anaf->first();
            } catch (\Throwable $th) {
                return null;
            }

         });
//dd($company);
        // $company = cache([$cif => $anaf->first()], now()->addMinutes(10));
       // $company = $anaf->first();
        if($company) {
            $fullAddress = $company->getFullAddress();
            $judet = "";
            $adresa= "";
            if ($fullAddress != "") {
                    $adresas = explode(',', $fullAddress);
                    $judet = explode(' ',$adresas[0])[1];
                    array_shift($adresas);
                    $adresa = implode($adresas);
            }
            $result = [
                "Denumire" =>  $company->getName(),
                "CIF" =>  $company->getCIF(),
                "NrRegCom" => $company->getRegCom(),
                "Phone" => $company->getPhone(),
                "Sediu" => trim($adresa),
                "Judet" => $judet,
                "RO" => $company->getTVA()->hasTVA() ? "RO" : "",
            ];
        } else {
            $result = [
                "Denumire" =>  "",
                "CIF" =>  "",
                "NrRegCom" =>"",
                "Phone" => "",
                "Sediu" => "",
                "Judet" => "",
                "RO" => "",
            ];
        }
        return new ClientResource($result);
    }


    public function newclientfromanaf($cif)
    {
        $anaf = new \Itrack\Anaf\Client();
        $anaf->addCif($cif .'1', Carbon::now()->toDateString());
        $company = cache()->remember('client-' . $cif, 3600, function () use ($anaf, $cif) {
            Log::debug('get cif from db - ' . $cif);
            try {
                return $anaf->first();
            } catch (\Throwable $th) {
                return null;
            }
        });

        $client = new Client();
        $client->CIF = $cif;
        if ($company) {
            $fullAddress = $company->getFullAddress();
            $judet = "";
            $adresa= "";
            if ($fullAddress != "") {
                    $adresas = explode(',', $fullAddress);
                    $judet = explode(' ',$adresas[0])[1];
                    array_shift($adresas);
                    $adresa = implode($adresas);
            }

            $client->Denumire =  $company->getName();
            $client->CIF =  $company->getCIF();
            $client->NrRegCom = $company->getRegCom();
            $client->Phone = $company->getPhone();
            $client->Sediu = trim($adresa);
            $client->Judet = $judet;
            $client->RO = $company->getTVA()->hasTVA() ? "RO" : "";
        }
        return new ClientResource($client);
    }

	public function update(ClientRequest $request, Client $client)
	{
		$client->update($request->validated());
		return new ClientResource($client);
	}
	public function updatecif(ClientRequest $request, $CIF)
	{
        //         $e = new \Exception();
//         echo('<PRE>');
//         var_dump($e->getTraceAsString());
// die();
 //        echo('<PRE>');

      //  dd($request);
  //      Log::debug('update client CIF - ' . $CIF);
        $client = Client::whereCif($CIF)->first();
        $rv = $request->validated();
//        print_r($rv);
 //       die();
		$client->update($rv);
		return new ClientResource($client);
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

public function confirmaresold($CIF, $Data)
{
    $client = Client::find($CIF);
    $html = $this->buildconfirmasoldhtml($client, $Data);

    $denumire =  preg_replace("/[^a-z0-9\_\-\.]/i", '_',  $client->Denumire);
    $fileName =
        'Confirmare_sold-' . $Data . '-' . $denumire  . '.pdf';
    $this->html2pdf($html, $fileName);
}


public function buildconfirmasoldhtml(Client $client, $data)
{
    $html = file_get_contents(__DIR__ . '/templates/ConfirmareSoldTemplate.html');
    $htmlrow = file_get_contents(__DIR__ . '/templates/ConfirmareSoldRandTemplate.html');

    $dataf = date("d-m-Y", strtotime($data));

    $facturineachitate = $client->facturineachitate;
    $htmlResult = '';
    if ($facturineachitate->count() > 0)
    {
        $furnizori = Furnizor::all();

        foreach($furnizori as $furnizor) {
            $total = 0;
            foreach($facturineachitate as $factura) {
                if ($factura->Data <= $data && $factura->Furnizor == $furnizor->id ) {
                    $total += $factura->Sold;
                }
            }

            if ($total) {
                if ($htmlResult != '')
                {
                    $htmlResult .= '<pagebreak>';

                }
                $htmlRowResult = '';
                foreach($facturineachitate as $factura) {
                    if ($factura->Data <= $data && $factura->Furnizor == $furnizor->id ) {
                        $replaceRow =
                        [
                            '{numar factura}' => $factura->SerieNumar,
                            '{data factura}' => date("d/m/Y", strtotime($factura->Data)),
                            '{text servicii}' => '',
                            '{sold factura}' => $factura->Sold,
                        ];
                        $htmlRowResult .= strtr($htmlrow, $replaceRow);
                    }
                }

                $replace =
                [
                    '{furnizor nume}'    => $furnizor->Denumire,
                    '{furnizor nr orc}'  => $furnizor->NrRegCom,
                    '{furnizor CUI}'     => $furnizor->CUI,
                    '{furnizor sediul}'  => $furnizor->Sediu,
                    '{furnizor judetul}' => $furnizor->Judet,
                    '{furnizor contul}'  => $furnizor->ContBancar,
                    '{furnizor banca}'   => $furnizor->Banca,
                    '{cumparator nume}'  => $client->Denumire,
                    '{cumparator nr orc}' => $client->NrRegCom,
                    '{cumparator CUI}'   =>  $client->CIF,
                    '{data sold}'        => $dataf,
                    '{sold total}'       => $total,
                    '{facturi}' => $htmlRowResult,
                ];
                $htmlResult .= strtr($html, $replace);
            }
        }
    }
   //  echo( '<pre>');
   //  print_r( $htmlResult );
   //  die();
   $htmlResult =
   '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Confirmare sold ' . $dataf . ' ' .  $client->Denumire . '</title>
    </head>
    <body lang=RO style="font-size:10.0pt;font-family:Calibri">' .
    $htmlResult .
    '</body></html>';

    return $htmlResult;
}

//====================










	public function destroy(Client $client)
	{
		$client->delete();
		return response()->noContent();
	}
}
