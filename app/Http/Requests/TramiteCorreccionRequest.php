<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CatalogoArchivo;
use App\Models\Tramite;
use App\Models\SeccionRevision;

class TramiteCorreccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Validación mínima - solo verificar que los archivos sean válidos si se suben
        $rules = [];

        // Validar archivos de corrección solo si se suben
        if ($this->hasFile('documentos_correccion')) {
            foreach ($this->file('documentos_correccion', []) as $archivoId => $file) {
                if ($file) {
                    // 5MB para PDF e imágenes; mantener 10MB cuando aplique en mensajes
                    $rules["documentos_correccion.{$archivoId}"] = 'file|mimes:pdf,png,jpg,jpeg|max:5120';
                }
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        // Mensajes solo para archivos si se suben
        if ($this->hasFile('documentos_correccion')) {
            foreach ($this->file('documentos_correccion', []) as $archivoId => $file) {
                if ($file) {
                    $messages["documentos_correccion.{$archivoId}.file"] = 'El archivo debe ser un archivo válido.';
                    $messages["documentos_correccion.{$archivoId}.mimes"] = 'El archivo debe ser de tipo: PDF, PNG, JPG, JPEG.';
                    $messages["documentos_correccion.{$archivoId}.max"] = 'El archivo no puede ser mayor a 5MB.';
                }
            }
        }

        return $messages;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            \Log::info('Validando formulario de corrección de trámite', [
                'input_data' => $this->all(),
                'files' => $this->allFiles(),
                'errors_count' => $validator->errors()->count()
            ]);
            
            if ($validator->errors()->count() > 0) {
                \Log::error('Errores de validación encontrados', [
                    'errors' => $validator->errors()->toArray()
                ]);
            }
        });
    }

    private function getTramite(): ?Tramite
    {
        $tramiteId = $this->route('tramite');
        if (is_numeric($tramiteId)) {
            return Tramite::find($tramiteId);
        }
        return null;
    }

    private function obtenerSeccionesRechazadas(Tramite $tramite): array
    {
        return SeccionRevision::where('tramite_id', $tramite->id)
            ->where('estado', 'Rechazado')
            ->pluck('seccion')
            ->toArray();
    }

    private function obtenerArchivosRechazados(Tramite $tramite): array
    {
        return $tramite->archivos()
            ->where('status', 'Rechazado')
            ->pluck('catalogo_archivo_id')
            ->toArray();
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
