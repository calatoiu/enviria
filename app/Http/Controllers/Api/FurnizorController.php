<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\FurnizorRequest;
use App\Http\Resources\FurnizorResource;
use App\Models\Furnizor;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FurnizorController extends Controller
{
	public function index()
	{
		return FurnizorResource::collection(Furnizor::all());
	}
	public function store(FurnizorRequest $request)
	{
		$furnizor = Furnizor::create($request->validated());
		return new FurnizorResource($furnizor);
	}
	public function show(Furnizor $furnizor)
	{
		return new FurnizorResource($furnizor);
	}
	public function showw($id)
	{
        $furnizor = Furnizor::find($id);
//         echo ('<PRE>');
//         print_r($furnizor);
//         print_r(new FurnizorResource($furnizor));
// die();

//return '{"data":{"CIF":18993268,"Denumire":"AGRO C.H.P. PROD SRL","ContBancar":"RO85INGB0000999905016706","Banca":"ING BANK CONSTANTA ","Sediu":"Str. Salcamului nr. 1, loc. Vadu, com. Corbu","Judet":"Constanta","NrRegCom":"J13\/2714\/2006","RO":"RO","NrContract":"23","DataContract":"2019-09-26","Valoare":"250","NrAutorizatie":71,"DataAutorizatie":"2016-05-23","DataExpirareAutorizatie":"2021-05-23","Furnizor":"KEE","Note":""}}';
//        $furnizor->Judet = "Constanța";
		return new FurnizorResource($furnizor);
	}



	public function update(FurnizorRequest $request, Furnizor $furnizor)
	{
		$furnizor->update($request->validated());
		return new FurnizorResource($furnizor);
	}
	public function updatecif(FurnizorRequest $request, $CIF)
	{
        //         $e = new \Exception();
//         echo('<PRE>');
//         var_dump($e->getTraceAsString());
// die();
 //        echo('<PRE>');

//        dd($company);
        $furnizor = Furnizor::whereCif($CIF)->first();
        $rv = $request->validated();
//        print_r($rv);
 //       die();
		$furnizor->update($rv);
		return new FurnizorResource($furnizor);
	}
	public function destroy(Furnizor $furnizor)
	{
		$furnizor->delete();
		return response()->noContent();
	}
}
