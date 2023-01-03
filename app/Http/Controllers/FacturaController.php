<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Factura;

use App\Http\Requests\FacturaRequest;
use App\Http\Resources\FacturaResource;
use Illuminate\Support\Facades\Log;

use Inertia\Inertia;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBlade()
    {
          $facturi = Factura::orderBy('SerieNumar','ASC')
     //       ->paginate(1000)
             ->get(['SerieNumar', 'Data']);
//          return Inertia::render('Facturi/FacturiIndex', ['facturi' => $facturi]);

 //       return FacturaResource::collection(Factura::all());


        // load the view and pass the sharks
        return view('facturi.index', ['facturi' => $facturi]);

    }

    public function index()
    {
     //    echo( '<pre>');
        // print_r( $request);
     //   print_r('FacturaController.index');
     //    die();
     //   $header = $request->header();
      //  Log::debug('FacturaController.index');

        $facturi = Factura::orderBy('SerieNumar','DESC')->paginate(5);
        return Inertia::render('Facturi/FacturiIndex', ['facturi' => $facturi]);
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacturaRequest $request)
    {
//
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(FacturaRequest $request, Factura $factura)
    {
//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
//
    }
}
