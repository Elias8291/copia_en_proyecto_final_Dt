<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CatalogoArchivo;
use App\Models\Tramite;
use App\Models\SeccionRevision;
use App\Support\ArchivosValidation;
use App\Services\RfcProveedorService;

class TramiteCorreccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];

        if ($this->hasFile('documentos_correccion')) {
            foreach ($this->file('documentos_correccion', []) as $archivoId => $file) {
                if ($file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $extensiones = ArchivosValidation::extensionesPermitidasPorExtension($ext);
                    $maxKb = ArchivosValidation::maximoKbPorTipo($ext);
                    $rules["documentos_correccion.{$archivoId}"] = 'file|mimes:' . $extensiones . '|max:' . $maxKb;
                }
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        if ($this->hasFile('documentos_correccion')) {
            foreach ($this->file('documentos_correccion', []) as $archivoId => $file) {
                if ($file) {
                    $ext = strtolower($file->getClientOriginalExtension());
                    $labels = ArchivosValidation::etiquetasPorExtension($ext);
                    $maxMb = ArchivosValidation::maximoMbPorTipo($ext);
                    $messages["documentos_correccion.{$archivoId}.file"] = 'El archivo debe ser un archivo válido.';
                    $messages["documentos_correccion.{$archivoId}.mimes"] = 'El archivo debe ser de tipo: ' . $labels . '.';
                    $messages["documentos_correccion.{$archivoId}.max"] = 'El archivo no puede ser mayor a ' . $maxMb . 'MB.';
                }
            }
        }

        return $messages;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
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
        $rfcService = app(RfcProveedorService::class);
        return $rfcService->esPersonaMoral($tipoPersona, $rfc);
    }
}
