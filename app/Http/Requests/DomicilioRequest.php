<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomicilioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calle' => 'required|string|max:255',
            'entre_calle' => 'nullable|string|max:255',
            'y_calle' => 'nullable|string|max:255',
            'numero_exterior' => 'required|string|max:20',
            'numero_interior' => 'nullable|string|max:20',
            'colonia' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10|regex:/^[0-9]{5}$/',
            'municipio' => 'required|string|max:100',
            'asentamiento' => 'required|string|max:100',
            'estado_id' => 'required|exists:estados,id',
            'coordenada_id' => 'nullable|exists:coordenadas,id',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
        ];
    }

    public function messages(): array
    {
        return [
            'calle.required' => 'La calle es obligatoria.',
            'calle.max' => 'La calle no puede tener más de 255 caracteres.',
            'entre_calle.max' => 'La calle de referencia no puede tener más de 255 caracteres.',
            'y_calle.max' => 'La segunda calle de referencia no puede tener más de 255 caracteres.',
            'numero_exterior.required' => 'El número exterior es obligatorio.',
            'numero_exterior.max' => 'El número exterior no puede tener más de 20 caracteres.',
            'numero_interior.max' => 'El número interior no puede tener más de 20 caracteres.',
            'colonia.required' => 'La colonia es obligatoria.',
            'colonia.max' => 'La colonia no puede tener más de 255 caracteres.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.regex' => 'El código postal debe tener 5 dígitos numéricos.',
            'municipio.required' => 'El municipio es obligatorio.',
            'municipio.max' => 'El municipio no puede tener más de 100 caracteres.',
            'asentamiento.required' => 'El asentamiento es obligatorio.',
            'asentamiento.max' => 'El asentamiento no puede tener más de 100 caracteres.',
            'estado_id.required' => 'El estado es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no es válido.',
            'coordenada_id.exists' => 'Las coordenadas seleccionadas no son válidas.',
            'latitud.numeric' => 'La latitud debe ser un número.',
            'latitud.between' => 'La latitud debe estar entre -90 y 90.',
            'longitud.numeric' => 'La longitud debe ser un número.',
            'longitud.between' => 'La longitud debe estar entre -180 y 180.',
        ];
    }
} 