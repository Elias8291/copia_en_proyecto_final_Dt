<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatosGeneralesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'razon_social' => 'required|string|max:255',
            'rfc' => 'required|string|max:13|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
            'curp' => 'nullable|string|max:18|regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/',
            'pagina_web' => 'nullable|url|max:255',
            'telefono' => 'required|string|max:50|regex:/^[0-9\s\(\)\-\+]+$/',
            'nombre_contacto' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'correo_contacto' => 'required|email|max:255',
            'telefono_contacto' => 'required|string|max:50|regex:/^[0-9\s\(\)\-\+]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.max' => 'La razón social no puede tener más de 255 caracteres.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.max' => 'El RFC no puede tener más de 13 caracteres.',
            'rfc.regex' => 'El formato del RFC no es válido.',
            'curp.max' => 'La CURP no puede tener más de 18 caracteres.',
            'curp.regex' => 'El formato de la CURP no es válido.',
            'pagina_web.url' => 'La página web debe tener un formato válido.',
            'pagina_web.max' => 'La página web no puede tener más de 255 caracteres.',
            'telefono.required' => 'El teléfono del proveedor es obligatorio.',
            'telefono.regex' => 'El teléfono del proveedor debe contener solo números, espacios, paréntesis, guiones y signos más.',
            'nombre_contacto.required' => 'El nombre del contacto es obligatorio.',
            'nombre_contacto.max' => 'El nombre del contacto no puede tener más de 255 caracteres.',
            'cargo.required' => 'El cargo del contacto es obligatorio.',
            'cargo.max' => 'El cargo del contacto no puede tener más de 255 caracteres.',
            'correo_contacto.required' => 'El correo del contacto es obligatorio.',
            'correo_contacto.email' => 'El correo del contacto debe tener un formato válido.',
            'correo_contacto.max' => 'El correo del contacto no puede tener más de 255 caracteres.',
            'telefono_contacto.required' => 'El teléfono del contacto es obligatorio.',
            'telefono_contacto.regex' => 'El teléfono del contacto debe contener solo números, espacios, paréntesis, guiones y signos más.',
        ];
    }
} 