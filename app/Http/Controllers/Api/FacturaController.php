<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\FacturaRequest;
use App\Http\Resources\FacturaResource;
use App\Models\Factura;
use App\Models\Client;
use SimpleXMLElement;
use Symfony\Component\HttpFoundation\Response;
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

    public function makefacturaxml($factura)
    {


        $xml = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <Invoice
                xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                xmlns:ns4="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 http://docs.oasis-open.org/ubl/os-UBL-2.1/xsd/maindoc/UBL-Invoice-2.1.xsd"/>'
        );
        $cbc = "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2";
        $cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2";

        $xml->addChild('CustomizationID', 'urn:cen.eu:en16931:2017#compliant#urn:efactura.mfinante.ro:CIUS-RO:1.0.1', $cbc);
        $xml->addChild('ID', $factura->SerieNumar, $cbc);
        $xml->addChild('IssueDate', $factura->Data, $cbc);
        $xml->addChild('DueDate', $factura->Data, $cbc);
        $xml->addChild('InvoiceTypeCode', '380', $cbc);
        $xml->addChild('Note', 'Note', $cbc); // daca nu nu
        $xml->addChild('DocumentCurrencyCode', 'RON', $cbc);
        $AccountingSupplierParty = $xml->addChild('AccountingSupplierParty', null, $cac);
        $Party = $AccountingSupplierParty->addChild('Party', null, $cac);
        $PartyName = $Party->addChild('PartyName', null, $cac);
        $PartyName->addChild('Name', $factura->furnizor->Denumire, $cbc);
        $PostalAddress = $Party->addChild('PostalAddress', null, $cac);
        $PostalAddress->addChild('StreetName', $factura->furnizor->Sediu, $cbc);
        $PostalAddress->addChild('CityName', 'Constanta', $cbc); // !!!!!!

        $judete = ['AB' => 'alba', 'AR' => 'arad', 'AG' => 'arges', 'BC' => 'bacau', 'BH' => 'bihor', 'BN' => 'bistrita-nasaud', 'BT' => 'botosani', 'BV' => 'brasov', 'BR' => 'braila', 'BZ' => 'buzau', 'CS' => 'caras-severin', 'CL' => 'calarasi', 'CJ' => 'cluj', 'CT' => 'constanta', 'CV' => 'covasna', 'DB' => 'dambovita', 'DJ' => 'dolj', 'GL' => 'galati','GR' => 'giurgiu', 'GJ' => 'gorj', 'HR' => 'harghita', 'HD' => 'hunedoara', 'IL' => 'ialomita', 'IS' => 'iasi', 'IF' => 'ilfov', 'MM' => 'maramures', 'MH' => 'mehedinti', 'MS' => 'mures', 'SM' => 'satu mare', 'SJ' => 'salaj', 'SB' => 'sibiu', 'SV' => 'suceava', 'TR' => 'teleorman', 'TM' => 'timis', 'TL' => 'tulcea', 'VS' => 'vaslui', 'VL' => 'valcea', 'VN' => 'vrancea', 'B'	=>	'bucuresti',];
        $judeteInvers = array_flip($judete);
        $unwanted_array = array('ș'=>'s', 'ț'=>'t', 'ă'=>'a', 'â'=>'a', 'î'=>'i');

        $judet = strtolower($factura->furnizor->Judet);
        $judet = strtr( $judet, $unwanted_array );
        $judet = 'RO-' . $judeteInvers[$judet];
        $PostalAddress->addChild('CountrySubentity', $judet, $cbc);

        $PostalAddress->addChild('Country', null, $cac)->addChild('IdentificationCode', 'RO', $cbc);

        $PartyLegalEntity = $Party->addChild('PartyLegalEntity', null, $cac);
        $PartyLegalEntity->addChild('RegistrationName', $factura->furnizor->Denumire, $cbc);
        $PartyLegalEntity->addChild('CompanyID', $factura->furnizor->CUI, $cbc);
     //   $PartyLegalEntity->addChild('CompanyLegalForm', $factura->furnizor->NrRegCom, $cbc);

        $AccountingCustomerParty = $xml->addChild('AccountingCustomerParty', null, $cac);
        $Party = $AccountingCustomerParty->addChild('Party', null, $cac);
        $PartyName = $Party->addChild('PartyName', null, $cac);
        $denumireClient = preg_replace("/\&/", '&amp;',  $factura->client->Denumire);
        $PartyName->addChild('Name', $denumireClient, $cbc);
        $PostalAddress = $Party->addChild('PostalAddress', null, $cac);
        $PostalAddress->addChild('StreetName', $factura->client->Sediu, $cbc);
        $PostalAddress->addChild('CityName', 'Constanta', $cbc); // !!!!!!
        $judet = strtolower($factura->client->Judet);
        $judet = strtr( $judet, $unwanted_array );
        $judet = 'RO-' . $judeteInvers[$judet];
        $PostalAddress->addChild('CountrySubentity', $judet, $cbc);
        $PostalAddress->addChild('Country', null, $cac)->addChild('IdentificationCode', 'RO', $cbc);
        $PartyLegalEntity = $Party->addChild('PartyLegalEntity', null, $cac);
        $PartyLegalEntity->addChild('RegistrationName', $denumireClient, $cbc);
        $PartyLegalEntity->addChild('CompanyID', $factura->client->CIF, $cbc);
     //   $PartyLegalEntity->addChild('CompanyLegalForm', $factura->client->NrRegCom, $cbc);

        $xml->addChild('PaymentMeans', null, $cac)->addChild('PaymentMeansCode', '1', $cbc);

        $TaxTotal = $xml->addChild('TaxTotal', null, $cac);
        $TaxTotal->addChild('TaxAmount', '0.00', $cbc)->addAttribute('currencyID','RON');
        $TaxSubtotal = $TaxTotal->addChild('TaxSubtotal', null, $cac);
        $TaxSubtotal->addChild('TaxableAmount', $factura->Valoare, $cbc)->addAttribute('currencyID','RON');
        $TaxSubtotal->addChild('TaxAmount', '0.00', $cbc)->addAttribute('currencyID','RON');
        $TaxCategory = $TaxSubtotal->addChild('TaxCategory', null, $cac);
        $TaxCategory->addChild('ID', 'O', $cbc);
        $TaxCategory->addChild('TaxExemptionReasonCode', 'VATEX-EU-O', $cbc);
        $TaxCategory->addChild('TaxExemptionReason', 'Entitatea nu este inregistrata in scopuri de TVA', $cbc);
     //   $TaxCategory->addChild('Percent', '0.00', $cbc);
        $TaxCategory->addChild('TaxScheme', null, $cac)->addChild('ID', 'VAT', $cbc);

        $LegalMonetaryTotal = $xml->addChild('LegalMonetaryTotal', null, $cac);
        $LegalMonetaryTotal->addChild('LineExtensionAmount', $factura->Valoare, $cbc)->addAttribute('currencyID','RON');
        $LegalMonetaryTotal->addChild('TaxExclusiveAmount', $factura->Valoare, $cbc)->addAttribute('currencyID','RON');
        $LegalMonetaryTotal->addChild('TaxInclusiveAmount', $factura->Valoare, $cbc)->addAttribute('currencyID','RON');
        $LegalMonetaryTotal->addChild('PayableAmount', $factura->Valoare, $cbc)->addAttribute('currencyID','RON');

        $InvoiceLine = $xml->addChild('InvoiceLine', null, $cac);
        $InvoiceLine->addChild('ID', '1', $cbc);
        $InvoiceLine->addChild('InvoicedQuantity', '1', $cbc)->addAttribute('unitCode','MON');
        $InvoiceLine->addChild('LineExtensionAmount', $factura->Valoare, $cbc)->addAttribute('currencyID','RON');
        $Item = $InvoiceLine->addChild('Item', null, $cac);
        $Item->addChild('Name', 'Servicii mediu', $cbc);
        $ClassifiedTaxCategory = $Item->addChild('ClassifiedTaxCategory', null, $cac);
        $ClassifiedTaxCategory->addChild('ID', 'O', $cbc);
        $ClassifiedTaxCategory->addChild('TaxScheme', null, $cac)->addChild('ID', 'VAT', $cbc);
        $InvoiceLine->addChild('Price', null, $cac)->addChild('PriceAmount', $factura->Valoare, $cbc)->addAttribute('currencyID','RON');

        return $xml->asXML();
    }

    public function facturaxmlupload($SerieNumar)
    {
        $factura = Factura::find($SerieNumar);
        $facturaxml = $this->makefacturaxml($factura);
//         $uploadURL =           'https://api.anaf.ro/test/FCTEL/rest/upload?standard=UBL&cif=' . $factura->furnizor->CUI;
         $uploadURL = 'https://webserviceapl.anaf.ro/test/FCTEL/rest/upload?standard=UBL&cif=' . $factura->furnizor->CUI;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,            $uploadURL );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $facturaxml );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);


//          curl_close($ch);

        echo '<pre>';
        print_r($info);
        print_r($response);
        echo '</pre>';
        die();
// ////////////////////////////////////

//             // Make request
//             $curl = curl_init();
//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => $uploadURL,
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_TIMEOUT => 10,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => "POST",

//  //               CURLOPT_POSTFIELDS => json_encode($cifs),
//                 CURLOPT_HTTPHEADER => array(
//                     "Cache-Control: no-cache",
//                     "Content-Type: text/plain",
//                     "User-Agent: PostmanRuntime/7.35.0",
//                     "Accept: */*",
//                     "Accept-Encoding: gzip, deflate, br",
//                     "Connection: keep-alive"
//                 )
//             ));

//             $response = curl_exec($curl);
//             $info = curl_getinfo($curl);
//             curl_close($curl);

//             // Check http code
//             // if (!isset($info['http_code']) || $info['http_code'] !== 200) {
//             //     throw new Exceptions\ResponseFailed("Response status: {$info['http_code']} | Response body: {$response}");
//             // }

//             // Get items
//             $responseData = json_decode($response, true);

//             // // Check if have json because ANAF return errors in plain text
//             // if(json_last_error() !== JSON_ERROR_NONE) {
//             //     throw new Exceptions\ResponseFailed("Json parse error | Response body: {$response}");
//             // }

//             // // Check success stats
//             // if ("SUCCESS" !== $responseData['message'] || 200 !== $responseData['cod']) {
//             //     throw new Exceptions\RequestFailed("Response message: {$responseData['message']} | Response body: {$response}");
//             // }

//             return $responseData['found'];
//    /////////////////////////////////

    }


    public function facturaxml($SerieNumar)
    {
        $factura = Factura::find($SerieNumar);
        $facturaxml = $this->makefacturaxml($factura);

        $denumire =  preg_replace("/[^a-z0-9\_\-\.]/i", '_',  $factura->client->Denumire);
        $fileName =
            $factura->SerieNumar . '-' .
            $factura->Data . '-' .
            $denumire  . '.xml';

        $response = Response($facturaxml, 200);
        $response->header('Content-Type', 'text/xml');
        $response->header('Cache-Control', 'public');
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=' . $fileName . '');
        $response->header('Content-Transfer-Encoding', 'binary');
        return $response;
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
