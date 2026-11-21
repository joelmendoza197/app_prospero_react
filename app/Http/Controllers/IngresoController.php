<?php
namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Http\Requests\IngresoRequest;
use Illuminate\Http\Request;

class IngresoController extends Controller
{
    // GET /api/ingresos — solo los del usuario autenticado
    public function index(Request $request)
    {
        $ingresos = Ingreso::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($ingresos);
    }

    // POST /api/ingresos
    public function store(IngresoRequest $request)
    {
        $ingreso = Ingreso::create(array_merge(
            $request->validated(),
            ['user_id' => $request->user()->id]
        ));

        return response()->json($ingreso, 201);
    }

    // PUT /api/ingresos/{id}
    public function update(IngresoRequest $request, Ingreso $ingreso)
    {
        // Verificar que el ingreso pertenece al usuario
        if ($ingreso->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $ingreso->update($request->validated());
        return response()->json($ingreso);
    }

    // DELETE /api/ingresos/{id}
    public function destroy(Request $request, Ingreso $ingreso)
    {
        if ($ingreso->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $ingreso->delete();
        return response()->json(['message' => 'Ingreso eliminado']);
    }
}