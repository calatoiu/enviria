<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\FacturaController;
use App\Http\Controllers\Api\FurnizorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('clients', ClientController::class);
//Route::apiResource('facturas', FacturaController::class);


Route::get('/clienti/{CIF}', [ClientController::class, 'showw']);
Route::get('/anaf/{CIF}', [ClientController::class, 'anaf']);
Route::put('/clienti/{CIF}', [ClientController::class, 'updatecif']);

Route::get('/facturi/{CUI}', [FacturaController::class, 'indexcui']);
Route::get('/facturahtml/{SerieNumar}', [FacturaController::class, 'facturahtml']);
Route::get('/facturapdf/{SerieNumar}', [FacturaController::class, 'facturapdf']);
Route::get('/factura/{SerieNumar}', [FacturaController::class, 'showw']);
Route::get('/newfactura/{CUI}', [FacturaController::class, 'newfactura']);
Route::get('/textfactura', [FacturaController::class, 'textfactura']);

Route::get('/furnizori', [FurnizorController::class, 'index']);

// test!!!!
Route::get('/facturahtmlraw', [FacturaController::class, 'facturahtmlraw']);
