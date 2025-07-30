<?php

namespace App\Http\Requests;

use App\Services\Formularios\DatosGeneralesFormService;
use App\Services\Formularios\DireccionFormService;
use App\Services\Formularios\DatosConstitutivosFormService;
use App\Services\Formularios\ApoderadoLegalFormService;
use App\Services\Formularios\AccionistasFormService;
use App\Services\Formularios\ActividadesEconomicasFormService;
use App\Services\Formularios\DocumentosFormService;
use App\Services\DocumentosService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TramiteFormularioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Datos generales básicos
            'rfc' => [
                'required', 'string', 'min:10', 'max:13',
                function ($attribute, $value, $fail) {
                    $value = strtoupper(trim($value));

                    if (strlen($value) === 12) {
                        // Persona Moral: 3 letras + 6 números + 3 caracteres alfanuméricos
                        if (!preg_match('/^[A-ZÑ&]{3}[0-9]{6}[A-V1-9A-Z0-9]{3}$/', $value)) {
                            $fail('El RFC de persona moral no tiene un formato válido.');
                        }
                    } elseif (strlen($value) === 13) {
                        // Persona Física: 4 letras + 6 números + 3 caracteres alfanuméricos
                        if (!preg_match('/^[A-ZÑ&]{4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/', $value)) {
                            $fail('El RFC de persona física no tiene un formato válido.');
                        }
                    } else {
                        $fail('El RFC debe tener 12 caracteres (persona moral) o 13 caracteres (persona física).');
                    }
                }
            ],
            'razon_social' => 'required|string|min:3|max:255',
            'tipo_persona' => 'nullable|in:Física,Moral',
            'curp' => 'nullable|string|size:18|regex:/^[A-Z]{4}[0-9]{6}[A-Z0-9]{8}$/',
            'pagina_web' => 'nullable|url|max:255',
            // Actividades
            'actividades' => 'nullable|array|min:1',
            'actividades.*' => 'nullable',
            // Confirmación
            'confirma_datos' => 'nullable|sometimes|accepted',
        ];

                // Obtener validaciones de los servicios de formularios
        $datosGeneralesService = app(DatosGeneralesFormService::class);
        $direccionService = app(DireccionFormService::class);
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $accionistasService = app(AccionistasFormService::class);
        
        $rules = array_merge($rules, $datosGeneralesService->getValidationRules());
        $rules = array_merge($rules, $direccionService->getValidationRules());
        
        // Agregar validaciones de persona moral si es necesario
        if ($this->isPersonaMoral()) {
            $rules = array_merge($rules, $datosConstitutivosService->getValidationRules());
            $rules = array_merge($rules, $apoderadoLegalService->getValidationRules());
            $rules = array_merge($rules, $accionistasService->getValidationRules());
        }

        // Validación dinámica de documentos usando DocumentosService
        $documentosService = app(DocumentosService::class);
        $documentosRules = $documentosService->getValidationRules($this);
        $rules = array_merge($rules, $documentosRules);

        // Agregar validaciones del nuevo DocumentosFormService
        $documentosFormService = app(DocumentosFormService::class);
        $rules = array_merge($rules, $documentosFormService->getValidationRules());

        // Validaciones adicionales para Persona Moral
        if ($this->isPersonaMoral()) {
            // Solo aplicar validaciones si se están enviando campos de persona moral
            $personaMoralRules = $this->getPersonaMoralRules();

            // Filtrar reglas basándose en los campos enviados
            $filteredRules = [];
            foreach ($personaMoralRules as $field => $rule) {
                if ($this->hasField($field)) {
                    // Si el campo está presente, hacerlo requerido
                    $filteredRules[$field] = str_replace('nullable|', 'required|', $rule);
                }
            }

            $rules = array_merge($rules, $filteredRules);
        }

        return $rules;
    }

    /**
     * Reglas específicas para Persona Moral
     */
    private function getPersonaMoralRules(): array
    {
        return [
            // Datos constitutivos
            'numero_escritura' => 'nullable|string|min:1|max:255',
            'fecha_constitucion' => 'nullable|date|before_or_equal:today',
            'notario_nombre' => 'nullable|string|min:5|max:255',
            'entidad_federativa' => 'nullable|string|max:255',
            'notario_numero' => 'nullable|integer|min:1|max:999999',
            'numero_registro' => 'nullable|string|min:1|max:255',
            'fecha_inscripcion' => 'nullable|date|after_or_equal:fecha_constitucion|before_or_equal:today',
            // Apoderado legal
            'apoderado_nombre' => 'nullable|string|min:5|max:255',
            'apoderado_rfc' => 'nullable|string|size:13|regex:/^[A-ZÑ&]{4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
            // Accionistas
            'accionistas' => 'nullable|array|min:1',
            'accionistas.*.nombre' => 'nullable|string|min:5|max:255',
            'accionistas.*.rfc' => 'nullable|string|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
            'accionistas.*.porcentaje' => 'nullable|numeric|min:0.01|max:100',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        $messages = [
            'rfc.required' => 'El RFC es obligatorio.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'curp.regex' => 'La CURP no tiene un formato válido.',
            'actividades.*.exists' => 'La actividad seleccionada no es válida.',
            'documentos.*.mimes' => 'El tipo de archivo no es válido según el catálogo.',
            'documentos.*.max' => 'Cada archivo no debe exceder 50MB.',
        ];

        // Obtener mensajes de los servicios de formularios
        $datosGeneralesService = app(DatosGeneralesFormService::class);
        $direccionService = app(DireccionFormService::class);
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $accionistasService = app(AccionistasFormService::class);
        $documentosFormService = app(DocumentosFormService::class);
        
        $messages = array_merge($messages, $datosGeneralesService->getValidationMessages());
        $messages = array_merge($messages, $direccionService->getValidationMessages());
        $messages = array_merge($messages, $documentosFormService->getValidationMessages());
        
        // Agregar mensajes de persona moral si es necesario
        if ($this->isPersonaMoral()) {
            $messages = array_merge($messages, $datosConstitutivosService->getValidationMessages());
            $messages = array_merge($messages, $apoderadoLegalService->getValidationMessages());
            $messages = array_merge($messages, $accionistasService->getValidationMessages());
        }

        return $messages;
    }

    /**
     * Determine if this is a Persona Moral based on RFC length and tipo_persona field.
     */
    private function isPersonaMoral(): bool
    {
        $tipoPersona = $this->input('tipo_persona');
        if ($tipoPersona === 'Moral')
            return true;
        if ($tipoPersona === 'Física')
            return false;

        $rfc = $this->input('rfc');
        return $rfc && strlen(trim($rfc)) === 12;
    }

    /**
     * Check if persona moral fields are actually provided in the request
     */
    private function hasPersonaMoralFields(): bool
    {
        $personaMoralFields = [
            'numero_escritura', 'fecha_constitucion', 'notario_nombre',
            'entidad_federativa', 'notario_numero', 'numero_registro',
            'fecha_inscripcion', 'apoderado_nombre', 'apoderado_rfc',
            'accionistas'
        ];

        foreach ($personaMoralFields as $field) {
            if ($this->hasField($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a field or its nested fields are present in the request
     */
    private function hasField($field): bool
    {
        $input = $this->input($field);

        // Para campos anidados como accionistas
        if (is_array($input)) {
            return !empty(array_filter($input, function ($item) {
                return is_array($item) && !empty(array_filter($item));
            }));
        }

        return !empty($input);
    }
}
