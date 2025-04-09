<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacturaRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero' => 'required|unique:facturas,numero|string|max:255',
            'fecha' => 'required|date',
            'cliente' => 'required|string|max:255',
            'vendedor' => 'required|string|max:255'
        ];

        
    }

    public function messages(){
        return [
            'numero.required'=>"El campo 'numero' es requerido",
            'numero.unique'=>"El valor del campo 'numero' ya ha sido registrado",
            'fecha.required'=>"El campo 'fecha' es requerido",
            'cliente.required'=>"El campo 'nombre del cliente' es requerido",
            'vendedor.required'=>"El campo 'nombre del vendedor' es requerido"
            
        ];
    }
}
