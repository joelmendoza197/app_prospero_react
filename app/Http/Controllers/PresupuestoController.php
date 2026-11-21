<?php
namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Models\Ingreso;
use App\Http\Requests\PresupuestoRequest;
use App\Http\Requests\PresupuestoItemRequest;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    // GET /api/presupuestos?mes=2026-05
    public function index(Request $request)
    {
        $mes = $request->mes ?? now()->format('Y-m');

        $presupuestos = Presupuesto::where('user_id', $request->user()->id)
            ->where('mes', $mes)
            ->with('items')
            ->latest()
            ->get();

        $totalIngresos   = $this->calcularTotalIngresos($request->user()->id, $mes);
        $totalAsignado   = $presupuestos->sum('monto_asignado');
        $disponible      = $totalIngresos - $totalAsignado;

        return response()->json([
            'presupuestos'   => $presupuestos,
            'total_ingresos' => $totalIngresos,
            'total_asignado' => $totalAsignado,
            'disponible'     => $disponible,
        ]);
    }

    private function calcularTotalIngresos($userId, $mes)
    {
        [$anio, $mesNum] = explode('-', $mes);
        return Ingreso::where('user_id', $userId)
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mesNum)
            ->sum('monto');
    }

    // POST /api/presupuestos
    public function store(PresupuestoRequest $request)
    {
        $mes           = $request->mes;
        $totalIngresos = $this->calcularTotalIngresos($request->user()->id, $mes);
        $totalAsignado = Presupuesto::where('user_id', $request->user()->id)
            ->where('mes', $mes)
            ->sum('monto_asignado');
        $disponible = $totalIngresos - $totalAsignado;

        if ($request->monto_asignado > $disponible + 0.01) {
            return response()->json([
                'message'    => 'El monto asignado excede el disponible.',
                'disponible' => $disponible,
            ], 422);
        }

        $presupuesto = Presupuesto::create(array_merge(
            $request->validated(),
            ['user_id' => $request->user()->id]
        ));

        return response()->json($presupuesto->load('items'), 201);
    }   

    // PUT /api/presupuestos/{presupuesto}
    public function update(PresupuestoRequest $request, Presupuesto $presupuesto)
    {
        if ($presupuesto->user_id !== $request->user()->id)
            return response()->json(['message' => 'No autorizado'], 403);

        // Validar que el nuevo monto no exceda disponible (excluye el propio plan)
        $mes           = $request->mes ?? $presupuesto->mes;
        $totalIngresos = $this->calcularTotalIngresos($request->user()->id, $mes);
        $totalAsignado = Presupuesto::where('user_id', $request->user()->id)
            ->where('mes', $mes)
            ->where('id', '!=', $presupuesto->id)
            ->sum('monto_asignado');
        $disponible = $totalIngresos - $totalAsignado;

        if ($request->monto_asignado > $disponible + 0.01) {
            return response()->json([
                'message'    => 'El monto asignado excede el disponible.',
                'disponible' => $disponible,
            ], 422);
        }

        $presupuesto->update($request->validated());
        return response()->json($presupuesto->load('items'));
    }

    // DELETE /api/presupuestos/{presupuesto}
    public function destroy(Request $request, Presupuesto $presupuesto)
    {
        if ($presupuesto->user_id !== $request->user()->id)
            return response()->json(['message' => 'No autorizado'], 403);

        $presupuesto->delete();
        return response()->json(['message' => 'Presupuesto eliminado']);
    }

    // POST /api/presupuestos/{presupuesto}/items
    public function storeItem(PresupuestoItemRequest $request, Presupuesto $presupuesto)
    {
        $orden = $presupuesto->items()->max('orden') + 1;
        $item = $presupuesto->items()->create(array_merge(
            $request->validated(),
            ['orden' => $orden]
        ));
        return response()->json($item, 201);
    }

    // PUT /api/presupuestos/{presupuesto}/items/{item}
    public function updateItem(PresupuestoItemRequest $request, Presupuesto $presupuesto, PresupuestoItem $item)
    {
        $item->update($request->validated());
        return response()->json($item);
    }

    // DELETE /api/presupuestos/{presupuesto}/items/{item}
    public function destroyItem(Request $request, Presupuesto $presupuesto, PresupuestoItem $item)
    {
        $item->delete();
        return response()->json(['message' => 'Item eliminado']);
    }
}