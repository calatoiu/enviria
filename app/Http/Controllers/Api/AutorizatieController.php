<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\AutorizatieRequest;
use App\Http\Resources\AutorizatieResource;
use App\Models\Autorizatie;
use App\Models\Client;
use App\Models\Punctlucru;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Dompdf\Dompdf;


class AutorizatieController extends Controller
{
	public function index()
	{
		return AutorizatieResource::collection(Autorizatie::all());
	}

	// public function indexcui($CUI)
	// {
    //     $client = Client::with('punctelucru')->find($CUI);
    //     return AutorizatieResource::collection($client->punctelucru()->get());
	// }



	public function store(AutorizatieRequest $request)
	{
		$autorizatie = Autorizatie::create($request->validated());
		return new AutorizatieResource($autorizatie);
	}

	public function show(Autorizatie $autorizatie)
	{
		return new AutorizatieResource($autorizatie);
	}

	public function showw($AutorizatieID)
	{
        $autorizatie = Autorizatie::whereAutorizatieid($AutorizatieID)->first();
//dd($autorizatie);

 //       if($autorizatie)
		    return new AutorizatieResource($autorizatie);
 //       else
  //         return '{}'; //response()->noContent();
	}

    public function newautorizatie($CUI)
    {
        $autorizatie = new Autorizatie();
        $autorizatie->CIF = $CUI;

        return new AutorizatieResource($autorizatie);
    }


	public function update(AutorizatieRequest $request, Autorizatie $autorizatie)
	{
		$autorizatie->update($request->validated());
		return new AutorizatieResource($autorizatie);
	}

	public function updateautorizatieid(AutorizatieRequest $request, $AutorizatieID)
	{
        $autorizatie = Autorizatie::whereAutorizatieid($AutorizatieID)->first();
        $rv = $request->validated();
		$autorizatie->update($rv);
		return new AutorizatieResource($autorizatie);
	}

	public function destroy(Autorizatie $autorizatie)
	{
		$autorizatie->delete();
		return response()->noContent();
	}



}
