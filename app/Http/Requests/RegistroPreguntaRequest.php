<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistroPreguntaRequest extends FormRequest
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
            'enunciado'=>'required|string|max:255',
            'opciones' =>'required|array|min:2',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $habilitarRespuestaCorrecta = $this->input('habilitarRespuestaCorrecta');
            $opciones = $this->input('opciones', []);

            $algunaOpcionSinTexto = false;

            foreach ($opciones as $opcion) {
                if (!isset($opcion['texto']) || trim($opcion['texto']) === '') {
                    $algunaOpcionSinTexto = true;
                    break;
                }
            }

            if ($algunaOpcionSinTexto) {
                $validator->errors()->add('opciones', 'Cada opción debe tener un texto definido.');
            }

            if ($habilitarRespuestaCorrecta) {
                $algunaCorrecta = false;

                foreach ($opciones as $opcion) {
                    if (isset($opcion['is_correct']) && $opcion['is_correct']) {
                        $algunaCorrecta = true;
                        break;
                    }
                }

                if (!$algunaCorrecta) {
                    $validator->errors()->add('opciones', 'Debes marcar al menos una respuesta como correcta.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'enunciado.required' => 'Debe escribir el enunciado de la pregunta.',
            'opciones.required' => 'Debe proporcionar las opciones de respuesta.',
            'opciones.min' => 'Debe proporcionar minimo dos opciones de respuesta.'
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
