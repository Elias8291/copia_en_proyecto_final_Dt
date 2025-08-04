<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstitucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado_id' => 'required|exists:estados,id',
            'numero_escritura' => 'required|string|max:255',
            'numero_escritura_constitutiva' => 'required|string|max:255',
            'fecha_constitucion' => 'required|date|before_or_equal:today',
            'nombre_notario' => 'required|string|max:255',
            'numero_notario' => 'required|string|max:255',
            'numero_registro_publico' => 'required|string|max:255',
            'fecha_inscripcion' => 'required|date|after_or_equal:fecha_constitucion',
        ];
    }

    public function messages(): array
    {
        return [
            'estado_id.required' => 'El estado es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no es válido.',
            'numero_escritura.required' => 'El número de escritura es obligatorio.',
            'numero_escritura.max' => 'El número de escritura no puede tener más de 255 caracteres.',
            'numero_escritura_constitutiva.required' => 'El número de escritura constitutiva es obligatorio.',
            'numero_escritura_constitutiva.max' => 'El número de escritura constitutiva no puede tener más de 255 caracteres.',
            'fecha_constitucion.required' => 'La fecha de constitución es obligatoria.',
            'fecha_constitucion.date' => 'La fecha de constitución debe tener un formato válido.',
            'fecha_constitucion.before_or_equal' => 'La fecha de constitución no puede ser posterior a hoy.',
            'nombre_notario.required' => 'El nombre del notario es obligatorio.',
            'nombre_notario.max' => 'El nombre del notario no puede tener más de 255 caracteres.',
            'numero_notario.required' => 'El número del notario es obligatorio.',
            'numero_notario.max' => 'El número del notario no puede tener más de 255 caracteres.',
            'numero_registro_publico.required' => 'El número de registro público es obligatorio.',
            'numero_registro_publico.max' => 'El número de registro público no puede tener más de 255 caracteres.',
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date' => 'La fecha de inscripción debe tener un formato válido.',
            'fecha_inscripcion.after_or_equal' => 'La fecha de inscripción debe ser posterior o igual a la fecha de constitución.',
        ];
    }
} 