<?php

namespace App\Http\Requests;

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
            
            // Contacto
            'cargo' => 'nullable|string|max:255',
            'email_contacto' => 'required|email|max:255',
            'telefono' => 'required|string|min:10|max:15',
            
            // Domicilio
            'calle' => 'required|string|min:5|max:255',
            'numero_exterior' => 'required|string|max:20',
            'numero_interior' => 'nullable|string|max:20',
            'codigo_postal' => 'required|string|size:5|regex:/^[0-9]{5}$/',
            'asentamiento' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'estado_id' => 'nullable|integer|min:1',
            
            // Actividades
            'actividades' => 'nullable|array|min:1',
            'actividades.*' => 'nullable|integer|exists:actividades_economicas,id',
            
            // Confirmación
            'confirma_datos' => 'nullable|sometimes|accepted',
        ];

        // Validación dinámica de documentos usando DocumentosService
        $documentosService = app(DocumentosService::class);
        $documentosRules = $documentosService->getValidationRules($this);
        $rules = array_merge($rules, $documentosRules);

        // Validaciones adicionales para Persona Moral
        if ($this->isPersonaMoral()) {
            $rules = array_merge($rules, $this->getPersonaMoralRules());
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
            'numero_escritura' => 'required|string|min:1|max:255',
            'fecha_constitucion' => 'required|date|before_or_equal:today',
            'notario_nombre' => 'required|string|min:5|max:255',
            'entidad_federativa' => 'required|string|max:255',
            'notario_numero' => 'required|integer|min:1|max:999999',
            'numero_registro' => 'required|string|min:1|max:255',
            'fecha_inscripcion' => 'required|date|after_or_equal:fecha_constitucion|before_or_equal:today',
            
            // Apoderado legal
            'apoderado_nombre' => 'required|string|min:5|max:255',
            'apoderado_rfc' => 'required|string|size:13|regex:/^[A-ZÑ&]{4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
            
            // Accionistas
            'accionistas' => 'required|array|min:1',
            'accionistas.*.nombre' => 'required|string|min:5|max:255',
            'accionistas.*.rfc' => 'required|string|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
            'accionistas.*.porcentaje' => 'required|numeric|min:0.01|max:100',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'rfc.required' => 'El RFC es obligatorio.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'curp.regex' => 'La CURP no tiene un formato válido.',
            'email_contacto.required' => 'El correo electrónico es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'calle.required' => 'La calle es obligatoria.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'actividades.*.exists' => 'La actividad seleccionada no es válida.',
            'documentos.*.mimes' => 'El tipo de archivo no es válido según el catálogo.',
            'documentos.*.max' => 'Cada archivo no debe exceder 10MB.',
        ];
    }

    /**
     * Determine if this is a Persona Moral based on RFC length and tipo_persona field.
     */
    private function isPersonaMoral(): bool
    {
        $tipoPersona = $this->input('tipo_persona');
        if ($tipoPersona === 'Moral') return true;
        if ($tipoPersona === 'Física') return false;
        
        $rfc = $this->input('rfc');
        return $rfc && strlen(trim($rfc)) === 12;
    }
} 