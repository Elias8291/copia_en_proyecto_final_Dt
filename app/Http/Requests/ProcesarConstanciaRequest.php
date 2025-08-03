<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcesarConstanciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document' => 'required|file|mimes:pdf|max:5120',
            'sat_rfc' => 'required|string',
            'sat_nombre' => 'required|string',
            'sat_tipo_persona' => 'required|string',
            'sat_email' => 'nullable|email',
            'qr_url' => 'nullable|url',
            'sat_curp' => 'nullable|string',
            // Datos del domicilio
            'sat_calle' => 'nullable|string',
            'sat_numero_exterior' => 'nullable|string',
            'sat_numero_interior' => 'nullable|string',
            'sat_colonia' => 'nullable|string',
            'sat_cp' => 'nullable|string',
            'sat_municipio' => 'nullable|string',
            'sat_entidad_federativa' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'document.required' => 'Debe seleccionar un archivo.',
            'document.file' => 'El archivo no es válido.',
            'document.mimes' => 'El archivo debe ser un PDF.',
            'document.max' => 'El archivo no puede ser mayor a 5MB.',
            'sat_rfc.required' => 'Los datos fiscales son requeridos.',
            'sat_nombre.required' => 'Los datos fiscales son requeridos.',
            'sat_tipo_persona.required' => 'Los datos fiscales son requeridos.',
        ];
    }
} 