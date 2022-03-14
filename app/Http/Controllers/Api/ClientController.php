<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mpdf\Tag\Tr;

class ClientController extends Controller
{
	public function index()
	{
		return ClientResource::collection(Client::all());
	}
	public function store(ClientRequest $request)
	{
		$client = Client::create($request->validated());
		return new ClientResource($client);
	}
	public function show(Client $client)
	{
		return new ClientResource($client);
	}
	public function showw($CIF)
	{
        $client = Client::whereCif($CIF)->first();
		return new ClientResource($client);
	}

    public function anaf($cif)
    {
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


	public function destroy(Client $client)
	{
		$client->delete();
		return response()->noContent();
	}
}
