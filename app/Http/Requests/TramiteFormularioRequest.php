<?php

namespace App\Http\Requests;

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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Limpiar y convertir datos antes de validación
        $data = $this->all();
        $originalData = $data; // Para comparar cambios
        
        \Illuminate\Support\Facades\Log::info('Datos antes de preparación', [
            'estado_id' => $data['estado_id'] ?? 'NOT_SET',
            'estado_id_type' => gettype($data['estado_id'] ?? null),
            'municipio' => $data['municipio'] ?? 'NOT_SET',
            'actividades' => $data['actividades'] ?? 'NOT_SET',
            'actividades_type' => gettype($data['actividades'] ?? null),
            'confirma_datos' => $data['confirma_datos'] ?? 'NOT_SET',
            // 🎯 DEBUG APODERADO LEGAL
            'apoderado_nombre' => $data['apoderado_nombre'] ?? 'NOT_SET',
            'apoderado_rfc' => $data['apoderado_rfc'] ?? 'NOT_SET',
            'poder_numero_escritura' => $data['poder_numero_escritura'] ?? 'NOT_SET',
            'poder_notario_nombre' => $data['poder_notario_nombre'] ?? 'NOT_SET',
        ]);
        
        // Convertir estado_id vacío a null
        if (isset($data['estado_id']) && $data['estado_id'] === '') {
            $data['estado_id'] = null;
        }
        
        // Convertir municipio_id vacío a null  
        if (isset($data['municipio_id']) && $data['municipio_id'] === '') {
            $data['municipio_id'] = null;
        }
        
        // Limpiar campos de texto vacíos
        $camposTexto = ['asentamiento', 'municipio', 'numero_interior', 'cargo', 'pagina_web'];
        foreach ($camposTexto as $campo) {
            if (isset($data[$campo]) && trim($data[$campo]) === '') {
                $data[$campo] = null;
            }
        }
        
        // Si no hay actividades, asegurar que sea null en lugar de array vacío
        if (isset($data['actividades']) && is_array($data['actividades']) && empty($data['actividades'])) {
            $data['actividades'] = null;
        }
        
        \Illuminate\Support\Facades\Log::info('Datos después de preparación', [
            'estado_id' => $data['estado_id'] ?? 'NOT_SET',
            'estado_id_type' => gettype($data['estado_id'] ?? null),
            'municipio' => $data['municipio'] ?? 'NOT_SET',
            'actividades' => $data['actividades'] ?? 'NOT_SET',
            'actividades_type' => gettype($data['actividades'] ?? null),
            'confirma_datos' => $data['confirma_datos'] ?? 'NOT_SET',
            'cambios_realizados' => $originalData !== $data,
            // 🎯 DEBUG APODERADO LEGAL DESPUÉS
            'apoderado_nombre_after' => $data['apoderado_nombre'] ?? 'NOT_SET',
            'apoderado_rfc_after' => $data['apoderado_rfc'] ?? 'NOT_SET',
            'poder_numero_escritura_after' => $data['poder_numero_escritura'] ?? 'NOT_SET',
            'poder_notario_nombre_after' => $data['poder_notario_nombre'] ?? 'NOT_SET',
        ]);
        
        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            // Datos generales básicos
            'rfc' => [
                'required',
                'string',
                'min:10',
                'max:13',
                function ($attribute, $value, $fail) {
                    // Limpiar y normalizar el RFC
                    $value = strtoupper(trim($value));
                    
                    // Log para debugging
                    \Illuminate\Support\Facades\Log::info('Validando RFC', [
                        'original' => request($attribute),
                        'limpio' => $value,
                        'longitud' => strlen($value)
                    ]);
                    
                    // Verificar longitud
                    if (strlen($value) === 12) {
                        // Persona Moral: 3 letras + 6 números + 3 caracteres alfanuméricos
                        if (!preg_match('/^[A-ZÑ&]{3}[0-9]{6}[A-V1-9A-Z0-9]{3}$/', $value)) {
                            \Illuminate\Support\Facades\Log::warning('RFC Moral inválido', ['rfc' => $value]);
                            $fail('El RFC de persona moral no tiene un formato válido.');
                        } else {
                            \Illuminate\Support\Facades\Log::info('RFC Moral válido', ['rfc' => $value]);
                        }
                    } elseif (strlen($value) === 13) {
                        // Persona Física: 4 letras + 6 números + 3 caracteres alfanuméricos  
                        if (!preg_match('/^[A-ZÑ&]{4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/', $value)) {
                            \Illuminate\Support\Facades\Log::warning('RFC Física inválido', ['rfc' => $value]);
                            $fail('El RFC de persona física no tiene un formato válido.');
                        } else {
                            \Illuminate\Support\Facades\Log::info('RFC Física válido', ['rfc' => $value]);
                        }
                    } elseif (empty($value)) {
                        \Illuminate\Support\Facades\Log::warning('RFC vacío');
                        $fail('El RFC es obligatorio.');
                    } else {
                        \Illuminate\Support\Facades\Log::warning('RFC longitud incorrecta', [
                            'rfc' => $value,
                            'longitud' => strlen($value)
                        ]);
                        $fail('El RFC debe tener 12 caracteres (persona moral) o 13 caracteres (persona física).');
                    }
                }
            ],
            'razon_social' => 'required|string|min:3|max:255',
            'tipo_persona' => 'nullable|in:Física,Moral',
            'curp' => 'nullable|string|size:18|regex:/^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9]{2}$/',
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
            'estado_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !is_numeric($value)) {
                        $fail('El estado debe ser un valor numérico válido.');
                    }
                    if (!empty($value) && (int)$value < 1) {
                        $fail('Debe seleccionar un estado válido.');
                    }
                }
            ],
            
            // Actividades y documentos
            'actividades' => 'nullable|array|min:1',
            'actividades.*' => 'nullable|integer|exists:actividades_economicas,id',
            'documentos.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            
            // 🎯 APODERADO LEGAL (Solo Persona Moral)
            'apoderado_nombre' => 'nullable|string|min:3|max:255',
            'apoderado_rfc' => [
                'nullable',
                'string',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/',
                'size:13'
            ],
            
            // 🎯 PODER NOTARIAL (Solo Persona Moral)  
            'poder_numero_escritura' => 'nullable|string|max:255',
            'poder_fecha_constitucion' => 'nullable|date|before_or_equal:today',
            'poder_notario_nombre' => 'nullable|string|min:3|max:255',
            'poder_entidad_federativa' => 'nullable|string|max:255',
            'poder_notario_numero' => 'nullable|string|max:50',
            'poder_numero_registro' => 'nullable|string|max:255',
            'poder_fecha_inscripcion' => 'nullable|date|before_or_equal:today',
            
            // Confirmación
            'confirma_datos' => 'nullable|sometimes|accepted',
        ];

        // Validaciones adicionales para Persona Moral
        if ($this->isPersonaMoral()) {
            $rules = array_merge($rules, [
                // Datos constitutivos (requeridos para persona moral)
                'numero_escritura' => 'required|string|min:1|max:255',
                'fecha_constitucion' => 'required|date|before_or_equal:today',
                'notario_nombre' => 'required|string|min:5|max:255',
                'entidad_federativa' => 'required|string|max:255',
                'notario_numero' => 'required|integer|min:1|max:999999',
                'numero_registro' => 'required|string|min:1|max:255',
                'fecha_inscripcion' => 'required|date|after_or_equal:fecha_constitucion|before_or_equal:today',
                
                // Apoderado legal (requerido para persona moral)
                'apoderado_nombre' => 'required|string|min:5|max:255',
                'apoderado_rfc' => 'required|string|size:13|regex:/^[A-ZÑ&]{4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
                
                // Accionistas (al menos uno requerido para persona moral)
                'accionistas' => 'required|array|min:1',
                'accionistas.*.nombre' => 'required|string|min:5|max:255',
                'accionistas.*.rfc' => 'required|string|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
                'accionistas.*.porcentaje' => 'required|numeric|min:0.01|max:100',
            ]);
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            // RFC - Mensajes específicos ya manejados en la validación custom
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.min' => 'El RFC debe tener al menos 10 caracteres.',
            'rfc.max' => 'El RFC no debe exceder 13 caracteres.',
            
            // Razón social
            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.min' => 'La razón social debe tener al menos 3 caracteres.',
            
            // CURP
            'curp.size' => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.regex' => 'La CURP no tiene un formato válido.',
            
            // Contacto
            'email_contacto.required' => 'El correo electrónico es obligatorio.',
            'email_contacto.email' => 'El correo electrónico debe ser válido.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.min' => 'El teléfono debe tener al menos 10 dígitos.',
            
            // Domicilio
            'calle.required' => 'La calle es obligatoria.',
            'calle.min' => 'La calle debe tener al menos 5 caracteres.',
            'numero_exterior.required' => 'El número exterior es obligatorio.',
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.size' => 'El código postal debe tener 5 dígitos.',
            'codigo_postal.regex' => 'El código postal solo debe contener números.',
 
            // 'municipio.required' => 'El municipio es obligatorio.',  // Comentado temporalmente
            // 'estado_id.required' => 'El estado es obligatorio.',     // Comentado temporalmente
            
            // Actividades - Comentadas temporalmente para debugging
            // 'actividades.required' => 'Debe seleccionar al menos una actividad económica.',
            // 'actividades.min' => 'Debe seleccionar al menos una actividad económica.',
            'actividades.*.exists' => 'La actividad seleccionada no es válida.',
            
            // Documentos
            'documentos.*.mimes' => 'Solo se permiten archivos PDF, DOC, DOCX, JPG, JPEG y PNG.',
            'documentos.*.max' => 'Cada archivo no debe exceder 10MB.',
            
            // Confirmación - Comentadas temporalmente para debugging  
            // 'confirma_datos.required' => 'Debe confirmar que los datos son correctos.',
            // 'confirma_datos.accepted' => 'Debe aceptar que los datos proporcionados son correctos.',
            
            // Datos constitutivos (Persona Moral)
            'numero_escritura.required' => 'El número de escritura es obligatorio para personas morales.',
            'fecha_constitucion.required' => 'La fecha de constitución es obligatoria.',
            'fecha_constitucion.before_or_equal' => 'La fecha de constitución no puede ser futura.',
            'notario_nombre.required' => 'El nombre del notario es obligatorio.',
            'notario_nombre.min' => 'El nombre del notario debe tener al menos 5 caracteres.',
            'entidad_federativa.required' => 'La entidad federativa es obligatoria.',
            'notario_numero.required' => 'El número de notario es obligatorio.',
            'notario_numero.integer' => 'El número de notario debe ser un número entero.',
            'numero_registro.required' => 'El número de registro es obligatorio.',
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.after_or_equal' => 'La fecha de inscripción debe ser posterior o igual a la fecha de constitución.',
            'fecha_inscripcion.before_or_equal' => 'La fecha de inscripción no puede ser futura.',
            
            // Apoderado legal (Persona Moral)
            'apoderado_nombre.required' => 'El nombre del apoderado legal es obligatorio.',
            'apoderado_nombre.min' => 'El nombre del apoderado debe tener al menos 5 caracteres.',
            'apoderado_rfc.required' => 'El RFC del apoderado legal es obligatorio.',
            'apoderado_rfc.regex' => 'El RFC del apoderado no tiene un formato válido (debe ser de persona física con 13 caracteres).',
            
            // Accionistas (Persona Moral)
            'accionistas.required' => 'Debe agregar al menos un accionista.',
            'accionistas.min' => 'Debe agregar al menos un accionista.',
            'accionistas.*.nombre.required' => 'El nombre del accionista es obligatorio.',
            'accionistas.*.nombre.min' => 'El nombre del accionista debe tener al menos 5 caracteres.',
            'accionistas.*.rfc.required' => 'El RFC del accionista es obligatorio.',
            'accionistas.*.rfc.regex' => 'El RFC del accionista no tiene un formato válido.',
            'accionistas.*.porcentaje.required' => 'El porcentaje de participación es obligatorio.',
            'accionistas.*.porcentaje.min' => 'El porcentaje debe ser mayor a 0.',
            'accionistas.*.porcentaje.max' => 'El porcentaje no puede ser mayor a 100.',
        ];
    }

    /**
     * Determine if this is a Persona Moral based on RFC length and tipo_persona field.
     */
    private function isPersonaMoral(): bool
    {
        // Primero verificar si se especificó explícitamente el tipo de persona
        $tipoPersona = $this->input('tipo_persona');
        if ($tipoPersona === 'Moral') {
            return true;
        }
        if ($tipoPersona === 'Física') {
            return false;
        }
        
        // Si no se especificó, determinar por RFC
        $rfc = $this->input('rfc');
        return $rfc && strlen(trim($rfc)) === 12;
    }

    /**
     * Get validated data organized by sections.
     */
    public function getValidatedDataBySections(): array
    {
        $validated = $this->validated();

        return [
            'datos_generales' => [
                'rfc' => $validated['rfc'] ?? null,
                'razon_social' => $validated['razon_social'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
            ],
            'direccion' => [
                'calle' => $validated['calle'] ?? null,
                'numero_exterior' => $validated['numero_exterior'] ?? null,
                'codigo_postal' => $validated['codigo_postal'] ?? null,
                'asentamiento' => $validated['asentamiento'] ?? null,
                'municipio' => $validated['municipio'] ?? null,
                'estado_id' => $validated['estado_id'] ?? null,
            ],
            'contacto' => [
                'nombre_contacto' => $validated['razon_social'] ?? null,
                'cargo' => $validated['cargo'] ?? null,
                'correo_electronico' => $validated['email_contacto'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
            ],
            'actividades' => $validated['actividades'] ?? [],
            'instrumento_notarial' => $this->isPersonaMoral() ? [
                'numero_escritura' => $validated['numero_escritura'] ?? null,
                'fecha_constitucion' => $validated['fecha_constitucion'] ?? null,
                'notario_nombre' => $validated['notario_nombre'] ?? null,
                'entidad_federativa' => $validated['entidad_federativa'] ?? null,
                'notario_numero' => $validated['notario_numero'] ?? null,
                'numero_registro' => $validated['numero_registro'] ?? null,
                'fecha_inscripcion' => $validated['fecha_inscripcion'] ?? null,
            ] : null,
            'apoderado_legal' => $this->isPersonaMoral() ? [
                'nombre_apoderado' => $validated['apoderado_nombre'] ?? null,
                'rfc' => $validated['apoderado_rfc'] ?? null,
            ] : null,
            'accionistas' => $validated['accionistas'] ?? [],
            'archivos' => $this->file('documentos') ?? [],
        ];
    }
} 