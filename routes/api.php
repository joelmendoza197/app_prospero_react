<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\PresupuestoController;

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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('ingresos', IngresoController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('ingresos', IngresoController::class);
    Route::apiResource('deudas', DeudaController::class);
    Route::post('deudas/{deuda}/pagos', [DeudaController::class, 'registrarPago']);
    Route::delete('deudas/{deuda}/pagos/{cuota}', [DeudaController::class, 'eliminarPago']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('ingresos', IngresoController::class);
    Route::apiResource('deudas', DeudaController::class);
    Route::post('deudas/{deuda}/pagos', [DeudaController::class, 'registrarPago']);
    Route::delete('deudas/{deuda}/pagos/{cuota}', [DeudaController::class, 'eliminarPago']);

    // Presupuestos
    Route::apiResource('presupuestos', PresupuestoController::class);
    Route::post('presupuestos/{presupuesto}/items', [PresupuestoController::class, 'storeItem']);
    Route::put('presupuestos/{presupuesto}/items/{item}', [PresupuestoController::class, 'updateItem']);
    Route::delete('presupuestos/{presupuesto}/items/{item}', [PresupuestoController::class, 'destroyItem']);
});