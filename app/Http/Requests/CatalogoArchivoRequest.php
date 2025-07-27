<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogoArchivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $archivoId = $this->route('archivo')?->id;
        
        return [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'tipo_persona' => 'required|in:Física,Moral,Ambas',
            'tipo_archivo' => 'required|in:png,pdf,mp3',
            'es_visible' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 500 caracteres.',
            
            'tipo_persona.required' => 'El tipo de persona es obligatorio.',
            'tipo_persona.in' => 'El tipo de persona debe ser Física, Moral o Ambas.',
            
            'tipo_archivo.required' => 'El tipo de archivo es obligatorio.',
            'tipo_archivo.in' => 'El tipo de archivo debe ser png, pdf o mp3.',
            
            'es_visible.boolean' => 'El estado de visibilidad debe ser verdadero o falso.'
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'descripcion' => 'descripción',
            'tipo_persona' => 'tipo de persona',
            'tipo_archivo' => 'tipo de archivo',
            'es_visible' => 'visibilidad'
        ];
    }
}
