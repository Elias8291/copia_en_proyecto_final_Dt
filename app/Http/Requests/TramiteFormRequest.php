<?php

namespace App\Http\Requests;

use App\Models\CatalogoArchivo;
use App\Services\Formularios\DatosGeneralesFormService;
use App\Services\Formularios\DireccionFormService;
use App\Services\Formularios\ActividadesFormService;
use App\Services\Formularios\DocumentosFormService;
use App\Services\Formularios\PersonaMoralFormService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TramiteFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Campos ocultos
            'tipo_persona' => 'required|in:Física,Moral',
            'confirma_datos' => 'required|in:on',
            'formulario_simple' => 'nullable|in:true',
        ];

        // Obtener reglas de cada servicio
        $datosGeneralesService = new DatosGeneralesFormService();
        $direccionService = new DireccionFormService();
        $actividadesService = new ActividadesFormService();
        $documentosService = new DocumentosFormService();

        $rules = array_merge(
            $rules,
            $datosGeneralesService->getValidationRules(),
            $direccionService->getValidationRules(),
            $actividadesService->getValidationRules(),
            $documentosService->getValidationRules()
        );

        // Reglas específicas para Persona Moral
        if ($this->input('tipo_persona') === 'Moral') {
            $personaMoralService = new PersonaMoralFormService();
            $rules = array_merge($rules, $personaMoralService->getValidationRules());
        }

        return $rules;
    }

    /**
     * Get custom error messages for the validation rules.
     */
    public function messages(): array
    {
        $messages = [];

        // Obtener mensajes de cada servicio
        $datosGeneralesService = new DatosGeneralesFormService();
        $direccionService = new DireccionFormService();
        $actividadesService = new ActividadesFormService();
        $documentosService = new DocumentosFormService();

        $messages = array_merge(
            $messages,
            $datosGeneralesService->getValidationMessages(),
            $direccionService->getValidationMessages(),
            $actividadesService->getValidationMessages(),
            $documentosService->getValidationMessages()
        );

        // Mensajes específicos para Persona Moral
        if ($this->input('tipo_persona') === 'Moral') {
            $personaMoralService = new PersonaMoralFormService();
            $messages = array_merge($messages, $personaMoralService->getValidationMessages());
        }

        return $messages;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateAllDocumentsRequired($validator);
        });
    }

    /**
     * Validar que todos los documentos requeridos estén presentes
     */
    private function validateAllDocumentsRequired($validator)
    {
        $documentos = $this->input('documentos', []);
        $documentosRequeridos = $this->getDocumentosRequeridos();

        // Filtrar solo los documentos que realmente se enviaron
        $documentosEnviados = array_filter($documentos, function($archivo) {
            return $archivo && (is_array($archivo) ? !empty($archivo) : $archivo->isValid());
        });

        // Si no se envió ningún documento, no validar
        if (empty($documentosEnviados)) {
            return;
        }

        // Verificar que los documentos enviados sean válidos
        $documentosInvalidos = [];
        foreach ($documentosEnviados as $documentoId => $archivo) {
            if (!$archivo || (is_array($archivo) ? empty($archivo) : !$archivo->isValid())) {
                $documentosInvalidos[] = $documentoId;
            }
        }

        if (!empty($documentosInvalidos)) {
            // Obtener nombres de documentos inválidos
            $nombresDocumentos = \App\Models\CatalogoArchivo::whereIn('id', $documentosInvalidos)
                ->pluck('nombre')
                ->toArray();

            $validator->errors()->add('documentos', 'Los siguientes documentos tienen errores: ' . implode(', ', $nombresDocumentos));
        }
    }

    /**
     * Obtener la lista de documentos requeridos según el tipo de persona
     */
    private function getDocumentosRequeridos()
    {
        $tipoPersona = $this->input('tipo_persona');

        // Obtener documentos del catálogo según el tipo de persona
        $documentos = \App\Models\CatalogoArchivo::where('es_visible', true)
            ->where(function ($query) use ($tipoPersona) {
                $query
                    ->where('tipo_persona', $tipoPersona ?? 'Física')
                    ->orWhere('tipo_persona', 'Ambas');
            })
            ->pluck('id')
            ->toArray();

        return $documentos;
    }

    /**
     * Get custom attribute names for the validation rules.
     */
    public function attributes(): array
    {
        $attributes = [
            'tipo_persona' => 'tipo de persona',
            'confirma_datos' => 'confirmación de datos',
            'formulario_simple' => 'formulario simple',
        ];

        // Obtener atributos de cada servicio
        $datosGeneralesService = new DatosGeneralesFormService();
        $direccionService = new DireccionFormService();
        $actividadesService = new ActividadesFormService();
        $documentosService = new DocumentosFormService();

        $attributes = array_merge(
            $attributes,
            $datosGeneralesService->getValidationAttributes(),
            $direccionService->getValidationAttributes(),
            $actividadesService->getValidationAttributes(),
            $documentosService->getValidationAttributes()
        );

        // Atributos específicos para Persona Moral
        if ($this->input('tipo_persona') === 'Moral') {
            $personaMoralService = new PersonaMoralFormService();
            $attributes = array_merge($attributes, $personaMoralService->getValidationAttributes());
        }

        return $attributes;
    }
}
