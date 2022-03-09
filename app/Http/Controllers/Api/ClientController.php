<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
//         echo ('<PRE>');
//         print_r($client);
//         print_r(new ClientResource($client));
// die();

//return '{"data":{"CIF":18993268,"Denumire":"AGRO C.H.P. PROD SRL","ContBancar":"RO85INGB0000999905016706","Banca":"ING BANK CONSTANTA ","Sediu":"Str. Salcamului nr. 1, loc. Vadu, com. Corbu","Judet":"Constanta","NrRegCom":"J13\/2714\/2006","RO":"RO","NrContract":"23","DataContract":"2019-09-26","Valoare":"250","NrAutorizatie":71,"DataAutorizatie":"2016-05-23","DataExpirareAutorizatie":"2021-05-23","Furnizor":"KEE","Note":""}}';
//        $client->Judet = "Constanța";
		return new ClientResource($client);
	}

    public function anaf($cif)
    {
        $anaf = new \Itrack\Anaf\Client();

        $anaf->addCif($cif, Carbon::now()->toDateString());

         $company = cache()->remember('client-' . $cif, 3600, function () use ($anaf, $cif) {
            Log::debug('get cif from db - ' . $cif);
             return $anaf->first();
         });

        // $company = cache([$cif => $anaf->first()], now()->addMinutes(10));
       // $company = $anaf->first();

        $adresas = explode(',', $company->getFullAddress());
        $judet = explode(' ',$adresas[0])[1];
        array_shift($adresas);
        $adresa = implode($adresas);

        $result = [
            "Denumire" =>  $company->getName(),
            "CIF" =>  $company->getCIF(),
            "NrRegCom" => $company->getRegCom(),
            "Phone" => $company->getPhone(),
            "Sediu" => $adresa,
            "Judet" => $judet,
            "RO" => $company->getTVA()->hasTVA() ? "RO" : "",
      //      'Activ' => $company->isActive(),
        ];
        //return ["Denumire" => $company->getFullAddress(),];
        return new ClientResource($result);
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

//        dd($company);
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
