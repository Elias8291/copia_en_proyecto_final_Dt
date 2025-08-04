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
            $rules[$nombreCampo] = 'required|file|mimes:pdf,png,jpg,jpeg,mp3,mp4|max:51200';
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
            $messages[$nombreCampo . '.max'] = "El archivo '{$archivo->nombre}' no puede ser mayor a 50MB.";
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