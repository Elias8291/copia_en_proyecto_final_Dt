<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CatalogoArchivo;

class ArchivosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];
        
        // Obtener archivos requeridos según el tipo de persona
        $tipoPersona = $this->determinarTipoPersona();
        $archivosRequeridos = CatalogoArchivo::where('es_visible', true)
            ->where(function($query) use ($tipoPersona) {
                $query->where('tipo_persona', 'Ambas')
                      ->orWhere('tipo_persona', $tipoPersona);
            })
            ->get();
        
        // Crear reglas para cada archivo requerido
        foreach ($archivosRequeridos as $archivo) {
            $nombreCampo = 'documentos.' . \Str::slug($archivo->nombre);
            // Tamaños máximos por tipo (en KB)
            $maxSizesKb = [
                'pdf' => 5120,   // 5MB
                'mp4' => 10240,  // 10MB
                'png' => 5120,   // 5MB
                'jpg' => 5120,   // 5MB
                'jpeg' => 5120,  // 5MB
                'mp3' => 10240,  // 10MB
            ];
            $tipoArchivo = is_string($archivo->tipo_archivo) ? strtolower($archivo->tipo_archivo) : '';
            $maxKb = $maxSizesKb[$tipoArchivo] ?? 5120; // por defecto 5MB
            $rules[$nombreCampo] = 'required|file|mimes:pdf,png,jpg,jpeg,mp3,mp4|max:' . $maxKb;
        }
        
        return $rules;
    }

    public function messages(): array
    {
        $messages = [];
        
        // Obtener archivos requeridos según el tipo de persona
        $tipoPersona = $this->determinarTipoPersona();
        $archivosRequeridos = CatalogoArchivo::where('es_visible', true)
            ->where(function($query) use ($tipoPersona) {
                $query->where('tipo_persona', 'Ambas')
                      ->orWhere('tipo_persona', $tipoPersona);
            })
            ->get();
        
        // Crear mensajes para cada archivo requerido
        foreach ($archivosRequeridos as $archivo) {
            $nombreCampo = 'documentos.' . \Str::slug($archivo->nombre);
            $messages[$nombreCampo . '.required'] = "El archivo '{$archivo->nombre}' es obligatorio.";
            $messages[$nombreCampo . '.file'] = "El archivo '{$archivo->nombre}' debe ser válido.";
            $messages[$nombreCampo . '.mimes'] = "El archivo '{$archivo->nombre}' debe ser PDF, PNG, JPG, MP3 o MP4.";

            // Mensaje dinámico según tipo
            $maxSizesKb = [
                'pdf' => 5120,
                'mp4' => 10240,
                'png' => 5120,
                'jpg' => 5120,
                'jpeg' => 5120,
                'mp3' => 10240,
            ];
            $tipoArchivo = is_string($archivo->tipo_archivo) ? strtolower($archivo->tipo_archivo) : '';
            $maxKb = $maxSizesKb[$tipoArchivo] ?? 5120;
            $maxMb = (int) round($maxKb / 1024);
            $messages[$nombreCampo . '.max'] = "El archivo '{$archivo->nombre}' no puede ser mayor a {$maxMb}MB.";
        }
        
        return $messages;
    }
    
    private function determinarTipoPersona(): string
    {
        $rfc = $this->input('rfc') ?: $this->input('rfc_hidden');
        $tipoPersona = $this->input('tipo_persona') ?: $this->input('tipo_persona_hidden');
        
        // Si tenemos tipo_persona, usarlo directamente
        if ($tipoPersona === 'Moral') {
            return 'Moral';
        }
        
        // Si no, usar el RFC con la lógica del servicio
        if ($rfc) {
            $rfcService = app(\App\Services\RfcProveedorService::class);
            return $rfcService->determinarTipoPersona($rfc);
        }
        
        return 'Física';
    }
} 