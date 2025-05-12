<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistroEncuestaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'titulo'=>'required|string|max:255',
            'descripcion'=>'required|string|max:255',
            'duracion_encuesta'=>'sometimes|required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'titulo.required'=>'El campo título es requerido',
            'descripcion.required'=>'El campo descripcion es requerido',
            'duracion_encuesta.required'=>'El campo tiempo es requerido',
            'duracion_encuesta.min' => 'La encuesta debe tener mínimo un minuto de duración'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Errores de validación',
            'errors' => $validator->errors()
        ], 400));
    }
}
