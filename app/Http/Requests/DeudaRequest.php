<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeudaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:255',
            'valor_total' => 'required|numeric|min:0.01',
            'fecha_inicio'=> 'required|date',
            'meses'       => 'required|integer|min:1',
            'tipo'        => 'required|in:sin_interes,con_interes',
            'tasa_anual'  => 'required_if:tipo,con_interes|nullable|numeric|min:0.01|max:100',
            'metodo'      => 'required_if:tipo,con_interes|nullable|in:frances,aleman',
        ];
    }
}
