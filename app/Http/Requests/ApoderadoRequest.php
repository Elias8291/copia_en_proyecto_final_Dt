<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApoderadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_apoderado' => 'required|string|max:255',
            'rfc_apoderado' => 'required|string|max:13|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
            'numero_escritura_constitutiva_poder' => 'required|string|max:255',
            'numero_registro_publico_poder' => 'required|string|max:255',
            'fecha_inscripcion_poder' => 'required|date|before_or_equal:today',
            'nombre_notario_poder' => 'required|string|max:255',
            'numero_notario_poder' => 'required|string|max:255',
            'numero_escritura_poder' => 'required|string|max:255',
            'fecha_poder' => 'required|date|before_or_equal:today',
        ];
        
    }

    public function messages(): array
    {
        return [
            'nombre_apoderado.required' => 'El nombre del apoderado es obligatorio.',
            'nombre_apoderado.max' => 'El nombre del apoderado no puede tener más de 255 caracteres.',
            'rfc_apoderado.required' => 'El RFC del apoderado es obligatorio.',
            'rfc_apoderado.max' => 'El RFC del apoderado no puede tener más de 13 caracteres.',
            'rfc_apoderado.regex' => 'El formato del RFC del apoderado no es válido.',
            'numero_escritura_constitutiva_poder.required' => 'El número de escritura constitutiva del poder es obligatorio.',
            'numero_escritura_constitutiva_poder.max' => 'El número de escritura constitutiva del poder no puede tener más de 255 caracteres.',
            'numero_registro_publico_poder.required' => 'El número de registro público del poder es obligatorio.',
            'numero_registro_publico_poder.max' => 'El número de registro público del poder no puede tener más de 255 caracteres.',
            'fecha_inscripcion_poder.required' => 'La fecha de inscripción del poder es obligatoria.',
            'fecha_inscripcion_poder.date' => 'La fecha de inscripción del poder debe tener un formato válido.',
            'fecha_inscripcion_poder.before_or_equal' => 'La fecha de inscripción del poder no puede ser posterior a hoy.',
            'nombre_notario_poder.required' => 'El nombre del notario del poder es obligatorio.',
            'nombre_notario_poder.max' => 'El nombre del notario del poder no puede tener más de 255 caracteres.',
            'numero_notario_poder.required' => 'El número del notario del poder es obligatorio.',
            'numero_notario_poder.max' => 'El número del notario del poder no puede tener más de 255 caracteres.',
            'numero_escritura_poder.required' => 'El número de escritura del poder es obligatorio.',
            'numero_escritura_poder.max' => 'El número de escritura del poder no puede tener más de 255 caracteres.',
            'fecha_poder.required' => 'La fecha del poder es obligatoria.',
            'fecha_poder.date' => 'La fecha del poder debe tener un formato válido.',
            'fecha_poder.before_or_equal' => 'La fecha del poder no puede ser posterior a hoy.',
        ];
    }
} 