<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngresoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'monto'       => 'required|numeric|min:0.01',
            'descripcion' => 'required|string|max:255',
            'recurrencia' => 'required|in:ninguna,semanal,mensual',
            'fecha'       => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'monto.required'       => 'El monto es obligatorio.',
            'monto.min'            => 'El monto debe ser mayor a 0.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'recurrencia.in'       => 'La recurrencia debe ser: ninguna, semanal o mensual.',
            'fecha.required'       => 'La fecha es obligatoria.',
        ];
    }
}