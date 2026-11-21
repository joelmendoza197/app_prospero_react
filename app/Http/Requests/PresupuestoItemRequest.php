<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresupuestoItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool { return true; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nombre'              => 'required|string|max:255',
            'porcentaje'          => 'nullable|numeric',
            'monto_presupuestado' => 'required|numeric',
            'monto_real'          => 'nullable|numeric',
            'completado'          => 'boolean',
            'orden'               => 'integer|min:0',
        ];
    }
}
