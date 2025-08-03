<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccionistasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'accionistas' => 'required|array|min:1',
            'accionistas.*.nombre' => 'required|string|max:255',
            'accionistas.*.rfc' => 'required|string|max:13|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
            'accionistas.*.porcentaje_participacion' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'accionistas.required' => 'Debe agregar al menos un accionista.',
            'accionistas.array' => 'Los accionistas deben ser una lista válida.',
            'accionistas.min' => 'Debe agregar al menos un accionista.',
            'accionistas.*.nombre.required' => 'El nombre del accionista es obligatorio.',
            'accionistas.*.nombre.max' => 'El nombre del accionista no puede tener más de 255 caracteres.',
            'accionistas.*.rfc.required' => 'El RFC del accionista es obligatorio.',
            'accionistas.*.rfc.max' => 'El RFC del accionista no puede tener más de 13 caracteres.',
            'accionistas.*.rfc.regex' => 'El formato del RFC del accionista no es válido.',
            'accionistas.*.porcentaje_participacion.required' => 'El porcentaje de participación es obligatorio.',
            'accionistas.*.porcentaje_participacion.numeric' => 'El porcentaje de participación debe ser un número.',
            'accionistas.*.porcentaje_participacion.min' => 'El porcentaje de participación debe ser mayor o igual a 0.',
            'accionistas.*.porcentaje_participacion.max' => 'El porcentaje de participación debe ser menor o igual a 100.',
        ];
    }
} 