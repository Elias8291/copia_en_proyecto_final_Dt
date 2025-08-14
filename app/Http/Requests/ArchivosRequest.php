<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CatalogoArchivo;
use App\Support\ArchivosValidation;
use App\Services\RfcProveedorService;

class ArchivosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoPersona = $this->determinarTipoPersona();
        $archivosRequeridos = CatalogoArchivo::where('es_visible', true)
            ->where(function($query) use ($tipoPersona) {
                $query->where('tipo_persona', 'Ambas')
                      ->orWhere('tipo_persona', $tipoPersona);
            })
            ->get();
        
        return ArchivosValidation::construirReglasParaCatalogo($archivosRequeridos, false, 'documentos.');
    }

    public function messages(): array
    {
        $tipoPersona = $this->determinarTipoPersona();
        $archivosRequeridos = CatalogoArchivo::where('es_visible', true)
            ->where(function($query) use ($tipoPersona) {
                $query->where('tipo_persona', 'Ambas')
                      ->orWhere('tipo_persona', $tipoPersona);
            })
            ->get();
        
        return ArchivosValidation::construirMensajesParaCatalogo($archivosRequeridos, 'documentos.');
    }
    
    private function determinarTipoPersona(): string
    {
        $rfc = $this->input('rfc') ?: $this->input('rfc_hidden');
        $tipoPersona = $this->input('tipo_persona') ?: $this->input('tipo_persona_hidden');
        
        if ($tipoPersona === 'Moral') {
            return 'Moral';
        }
        
        if ($rfc) {
            $rfcService = app(RfcProveedorService::class);
            return $rfcService->determinarTipoPersona($rfc);
        }
        
        return 'Física';
    }
}
