<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\PunctlucruRequest;
use App\Http\Resources\PunctlucruResource;
use App\Models\Punctlucru;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Dompdf\Dompdf;


class PunctlucruController extends Controller
{
	public function index()
	{
		return PunctlucruResource::collection(Punctlucru::all());
	}

	public function indexcui($CUI)
	{
        $client = Client::with('punctelucru')->find($CUI);
        return PunctlucruResource::collection($client->punctelucru()->get());
	}



	public function store(PunctlucruRequest $request)
	{
		$punctlucru = Punctlucru::create($request->validated());
		return new PunctlucruResource($punctlucru);
	}

	public function show(Punctlucru $punctlucru)
	{
		return new PunctlucruResource($punctlucru);
	}

	public function showw($PunctLucruID)
	{
        $punctlucru = Punctlucru::wherePunctlucruid($PunctLucruID)->first();
//dd($punctlucru);

 //       if($punctlucru)
		    return new PunctlucruResource($punctlucru);
 //       else
  //         return '{}'; //response()->noContent();
	}

    public function newpunctlucru($CUI)
    {
        $punctlucru = new Punctlucru();
        $punctlucru->CIF = $CUI;

     //   $punctlucru->Continut = '';
     //   $punctlucru->Nota = '';
     //   $punctlucru->FaraContract = 0;
      //  $punctlucru->NotaText = 0;
        return new PunctlucruResource($punctlucru);
    }


	public function update(PunctlucruRequest $request, Punctlucru $punctlucru)
	{
		$punctlucru->update($request->validated());
		return new PunctlucruResource($punctlucru);
	}

	public function updatepunctlucruid(PunctlucruRequest $request, $PunctLucruID)
	{
        $punctlucru = Punctlucru::wherePunctlucruid($PunctLucruID)->first();
        $rv = $request->validated();
		$punctlucru->update($rv);
		return new PunctlucruResource($punctlucru);
	}

	public function destroy(Punctlucru $punctlucru)
	{
		$punctlucru->delete();
		return response()->noContent();
	}



}
