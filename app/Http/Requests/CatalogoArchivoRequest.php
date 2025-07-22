<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CatalogoArchivoRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta petición
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Obtener las reglas de validación para la petición
     */
    public function rules(): array
    {
        $archivoId = $this->route('archivo')?->id;

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('catalogo_archivos', 'nombre')->ignore($archivoId),
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:500',
            ],
            'tipo_persona' => [
                'required',
                Rule::in(['Física', 'Moral', 'Ambas']),
            ],
            'tipo_archivo' => [
                'required',
                Rule::in(['png', 'pdf', 'mp3']),
            ],
            'es_visible' => [
                'boolean',
            ],
        ];
    }

    /**
     * Obtener mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del archivo es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'nombre.unique' => 'Ya existe un archivo con este nombre.',

            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 500 caracteres.',

            'tipo_persona.required' => 'Debe seleccionar el tipo de persona.',
            'tipo_persona.in' => 'El tipo de persona debe ser: Física, Moral o Ambas.',

            'tipo_archivo.required' => 'Debe seleccionar el tipo de archivo.',
            'tipo_archivo.in' => 'El tipo de archivo debe ser: PDF, PNG o MP3.',

            'es_visible.boolean' => 'El estado de visibilidad debe ser verdadero o falso.',
        ];
    }

    /**
     * Obtener nombres de atributos personalizados
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del archivo',
            'descripcion' => 'descripción',
            'tipo_persona' => 'tipo de persona',
            'tipo_archivo' => 'tipo de archivo',
            'es_visible' => 'visibilidad',
        ];
    }

    /**
     * Preparar los datos para validación
     */
    protected function prepareForValidation(): void
    {
        // Limpiar y normalizar el nombre
        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => trim($this->nombre),
            ]);
        }

        // Limpiar la descripción
        if ($this->has('descripcion') && ! empty($this->descripcion)) {
            $this->merge([
                'descripcion' => trim($this->descripcion),
            ]);
        } else {
            $this->merge([
                'descripcion' => null,
            ]);
        }

        // Normalizar el boolean es_visible
        if (! $this->has('es_visible')) {
            $this->merge([
                'es_visible' => false,
            ]);
        }
    }

    /**
     * Configurar el validador después de la validación
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validación personalizada adicional si es necesaria
            if ($this->tipo_archivo === 'mp3' && $this->tipo_persona === 'Física') {
                // Ejemplo: podríamos agregar lógica de negocio específica
                // $validator->errors()->add('tipo_archivo', 'Los archivos MP3 no están permitidos para personas físicas.');
            }
        });
    }

    /**
     * Obtener datos validados con transformaciones
     */
    public function getValidatedData(): array
    {
        $validated = $this->validated();

        // Asegurar que es_visible sea boolean
        $validated['es_visible'] = $this->boolean('es_visible', false);

        return $validated;
    }
}
