<?php
namespace App\Http\Controllers;

use App\Models\Deuda;
use App\Models\DeudaPago;
use App\Http\Requests\DeudaRequest;
use App\Http\Requests\PagoRequest;
use Illuminate\Http\Request;

class DeudaController extends Controller
{
    // GET /api/deudas
    public function index(Request $request)
    {
        $deudas = Deuda::where('user_id', $request->user()->id)
            ->with('pagos')
            ->latest()
            ->get();

        return response()->json($deudas);
    }

    // POST /api/deudas
    public function store(DeudaRequest $request)
    {
        $deuda = Deuda::create(array_merge(
            $request->validated(),
            ['user_id' => $request->user()->id]
        ));

        return response()->json($deuda->load('pagos'), 201);
    }

    // PUT /api/deudas/{deuda}
    public function update(DeudaRequest $request, Deuda $deuda)
    {
        if ($deuda->user_id !== $request->user()->id)
            return response()->json(['message' => 'No autorizado'], 403);

        $deuda->update($request->validated());
        return response()->json($deuda->load('pagos'));
    }

    // DELETE /api/deudas/{deuda}
    public function destroy(Request $request, Deuda $deuda)
    {
        if ($deuda->user_id !== $request->user()->id)
            return response()->json(['message' => 'No autorizado'], 403);

        $deuda->delete();
        return response()->json(['message' => 'Deuda eliminada']);
    }

    // POST /api/deudas/{deuda}/pagos — registrar o actualizar pago de una cuota
    public function registrarPago(PagoRequest $request, Deuda $deuda)
    {
        if ($deuda->user_id !== $request->user()->id)
            return response()->json(['message' => 'No autorizado'], 403);

        $pago = DeudaPago::updateOrCreate(
            ['deuda_id' => $deuda->id, 'numero_cuota' => $request->numero_cuota],
            ['monto_pagado' => $request->monto_pagado, 'fecha_pago' => $request->fecha_pago]
        );

        return response()->json($pago);
    }

    // DELETE /api/deudas/{deuda}/pagos/{cuota} — desmarcar pago
    public function eliminarPago(Request $request, Deuda $deuda, $numeroCuota)
    {
        if ($deuda->user_id !== $request->user()->id)
            return response()->json(['message' => 'No autorizado'], 403);

        DeudaPago::where('deuda_id', $deuda->id)
            ->where('numero_cuota', $numeroCuota)
            ->delete();

        return response()->json(['message' => 'Pago eliminado']);
    }
}