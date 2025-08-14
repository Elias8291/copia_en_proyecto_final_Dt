<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CatalogoArchivo;

class TramiteFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        $datosGeneralesRequest = new DatosGeneralesRequest();
        $domicilioRequest = new DomicilioRequest();
        $actividadesRequest = new ActividadesRequest();

        $rules = array_merge(
            $rules,
            $datosGeneralesRequest->rules(),
            $domicilioRequest->rules(),
            $actividadesRequest->rules()
        );

        if ($this->esPersonaMoral()) {
            $constitucionRequest = new ConstitucionRequest();
            $accionistasRequest = new AccionistasRequest();
            $apoderadoRequest = new ApoderadoRequest();

            $rules = array_merge(
                $rules,
                $constitucionRequest->rules(),
                $accionistasRequest->rules(),
                $apoderadoRequest->rules()
            );
        }

        // Obtener reglas de validación dinámicas para archivos basadas en el catálogo
        $archivosRules = $this->obtenerReglasArchivosDinamicas();
        $rules = array_merge($rules, $archivosRules);

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        $datosGeneralesRequest = new DatosGeneralesRequest();
        $domicilioRequest = new DomicilioRequest();
        $actividadesRequest = new ActividadesRequest();

        $messages = array_merge(
            $messages,
            $datosGeneralesRequest->messages(),
            $domicilioRequest->messages(),
            $actividadesRequest->messages()
        );

        if ($this->esPersonaMoral()) {
            $constitucionRequest = new ConstitucionRequest();
            $accionistasRequest = new AccionistasRequest();
            $apoderadoRequest = new ApoderadoRequest();

            $messages = array_merge(
                $messages,
                $constitucionRequest->messages(),
                $accionistasRequest->messages(),
                $apoderadoRequest->messages()
            );
        }

        // Obtener mensajes de validación dinámicos para archivos
        $archivosMessages = $this->obtenerMensajesArchivosDinamicos();
        $messages = array_merge($messages, $archivosMessages);

        return $messages;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            \Log::info('Validando formulario de trámite', [
                'input_data' => $this->all(),
                'files' => $this->allFiles(),
                'errors_count' => $validator->errors()->count()
            ]);
            
            if ($validator->errors()->count() > 0) {
                \Log::error('Errores de validación encontrados', [
                    'errors' => $validator->errors()->toArray()
                ]);
            }
            
            // Validar archivos requeridos solo si no hay errores de validación básica
            if ($validator->errors()->count() === 0) {
                $this->validarArchivosRequeridos($validator);
            }
        });
    }

    private function obtenerReglasArchivosDinamicas(): array
    {
        $rules = [];
        
        try {
            $tipoPersona = $this->esPersonaMoral() ? 'Moral' : 'Física';
            $archivosRequeridos = CatalogoArchivo::where('es_visible', true)
                ->where(function($query) use ($tipoPersona) {
                    $query->where('tipo_persona', 'Ambas')
                          ->orWhere('tipo_persona', $tipoPersona);
                })
                ->get();

            foreach ($archivosRequeridos as $archivo) {
                $nombreCampo = 'documentos.' . \Str::slug($archivo->nombre);
                
                // Obtener tipos MIME permitidos según el tipo de archivo del catálogo
                $tiposMime = $this->obtenerTiposMimePorTipoArchivo($archivo->tipo_archivo);
                
                // En modo corrección, los archivos no son obligatorios inicialmente
                // En modo normal, son obligatorios
                $esModoCorreccion = $this->esModoCorreccion();
                $regla = $esModoCorreccion ? 'nullable' : 'required';

                // Tamaños máximos por tipo (en KB)
                $maxSizesKb = [
                    'pdf' => 5120,   // 5MB
                    'mp4' => 10240,  // 10MB
                    'png' => 5120,   // 5MB
                    'jpg' => 5120,   // 5MB
                    'jpeg' => 5120,  // 5MB
                    'gif' => 5120,   // 5MB
                    'webp' => 5120,  // 5MB
                    'mp3' => 10240,  // 10MB
                ];

                $tipoArchivo = is_string($archivo->tipo_archivo) ? strtolower($archivo->tipo_archivo) : '';
                $maxKb = $maxSizesKb[$tipoArchivo] ?? 5120; // por defecto 5MB

                $rules[$nombreCampo] = $regla . '|file|mimes:' . $tiposMime . '|max:' . $maxKb;
            }
        } catch (\Exception $e) {
            \Log::error('Error al obtener reglas de archivos dinámicas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return $rules;
    }

    private function obtenerMensajesArchivosDinamicos(): array
    {
        $messages = [];
        
        try {
            $tipoPersona = $this->esPersonaMoral() ? 'Moral' : 'Física';
            $archivosRequeridos = CatalogoArchivo::where('es_visible', true)
                ->where(function($query) use ($tipoPersona) {
                    $query->where('tipo_persona', 'Ambas')
                          ->orWhere('tipo_persona', $tipoPersona);
                })
                ->get();

            foreach ($archivosRequeridos as $archivo) {
                $nombreCampo = 'documentos.' . \Str::slug($archivo->nombre);
                $tiposPermitidos = $this->obtenerTiposPermitidosPorTipoArchivo($archivo->tipo_archivo);
                
                $messages[$nombreCampo . '.required'] = "El archivo '{$archivo->nombre}' es obligatorio.";
                $messages[$nombreCampo . '.file'] = "El archivo '{$archivo->nombre}' debe ser un archivo válido.";
                $messages[$nombreCampo . '.mimes'] = "El archivo '{$archivo->nombre}' debe ser de tipo: {$tiposPermitidos}.";

                // Mensaje de tamaño máximo dinámico por tipo
                $maxSizesKb = [
                    'pdf' => 5120,
                    'mp4' => 10240,
                    'png' => 5120,
                    'jpg' => 5120,
                    'jpeg' => 5120,
                    'gif' => 5120,
                    'webp' => 5120,
                    'mp3' => 10240,
                ];
                $tipoArchivo = is_string($archivo->tipo_archivo) ? strtolower($archivo->tipo_archivo) : '';
                $maxKb = $maxSizesKb[$tipoArchivo] ?? 5120;
                $maxMb = (int) round($maxKb / 1024);
                $messages[$nombreCampo . '.max'] = "El archivo '{$archivo->nombre}' no puede ser mayor a {$maxMb}MB.";
            }
        } catch (\Exception $e) {
            \Log::error('Error al obtener mensajes de archivos dinámicos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return $messages;
    }

    private function obtenerTiposMimePorTipoArchivo(string $tipoArchivo): string
    {
        return match($tipoArchivo) {
            'pdf' => 'pdf',
            'png' => 'png,jpg,jpeg,gif,webp',
            'mp3' => 'mp3,wav,ogg',
            'mp4' => 'mp4,avi,mov,wmv,flv,webm',
            default => 'pdf,png,jpg,jpeg,gif,webp,mp3,wav,ogg,mp4,avi,mov,wmv,flv,webm'
        };
    }

    private function obtenerTiposPermitidosPorTipoArchivo(string $tipoArchivo): string
    {
        return match($tipoArchivo) {
            'pdf' => 'PDF',
            'png' => 'PNG, JPG, JPEG, GIF, WEBP',
            'mp3' => 'MP3, WAV, OGG',
            'mp4' => 'MP4, AVI, MOV, WMV, FLV, WEBM',
            default => 'PDF, PNG, JPG, JPEG, GIF, WEBP, MP3, WAV, OGG, MP4, AVI, MOV, WMV, FLV, WEBM'
        };
    }

    private function validarArchivosRequeridos($validator)
    {
        try {
            // En modo corrección, no validar archivos requeridos automáticamente
            // La validación específica se maneja en el controlador
            if ($this->esModoCorreccion()) {
                return;
            }
            
            $tipoPersona = $this->esPersonaMoral() ? 'Moral' : 'Física';
            $archivosRequeridos = CatalogoArchivo::where('es_visible', true)
                ->where(function($query) use ($tipoPersona) {
                    $query->where('tipo_persona', 'Ambas')
                          ->orWhere('tipo_persona', $tipoPersona);
                })
                ->get();

            $documentos = $this->file('documentos', []);
            $archivosFaltantes = [];

            foreach ($archivosRequeridos as $archivo) {
                $nombreCampo = \Str::slug($archivo->nombre);
                
                // En modo normal, validar todos los archivos requeridos
                if (!isset($documentos[$nombreCampo]) || !$documentos[$nombreCampo]) {
                    $archivosFaltantes[] = $archivo->nombre;
                }
            }

            if (!empty($archivosFaltantes)) {
                $validator->errors()->add('archivos_faltantes', 'Los siguientes archivos son obligatorios: ' . implode(', ', $archivosFaltantes));
            }
        } catch (\Exception $e) {
            \Log::error('Error al validar archivos requeridos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $validator->errors()->add('archivos_error', 'Error al validar archivos: ' . $e->getMessage());
        }
    }

    private function esModoCorreccion(): bool
    {
        // Verificar si estamos en modo corrección basado en la URL o parámetros
        $path = $this->path();
        $method = $this->method();
        
        // Si la URL contiene 'edit' o el método es PUT, estamos en modo corrección
        return str_contains($path, 'edit') || $method === 'PUT';
    }

    private function esPersonaMoral(): bool
    {
        $rfc = $this->input('rfc') ?: $this->input('rfc_hidden') ?: $this->input('rfc_fallback');
        $tipoPersona = $this->input('tipo_persona') ?: $this->input('tipo_persona_hidden') ?: $this->input('tipo_persona_fallback');
        
        // Si tenemos tipo_persona, usarlo directamente
        if ($tipoPersona === 'Moral') {
            return true;
        }
        
        // Si no, usar el RFC con la lógica del servicio
        if ($rfc) {
            try {
                $rfcService = app(\App\Services\RfcProveedorService::class);
                return $rfcService->determinarTipoPersona($rfc) === 'Moral';
            } catch (\Exception $e) {
                \Log::error('Error al determinar tipo de persona', [
                    'rfc' => $rfc,
                    'error' => $e->getMessage()
                ]);
                return false;
            }
        }
        
        return false;
    }
}
