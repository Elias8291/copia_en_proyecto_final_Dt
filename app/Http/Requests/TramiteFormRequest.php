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

        $archivosMessages = $this->obtenerMensajesArchivosDinamicos();
        $messages = array_merge($messages, $archivosMessages);

        return $messages;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
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

            $rules = \App\Support\ArchivosValidation::construirReglasParaCatalogo(
                $archivosRequeridos,
                $this->esModoCorreccion(),
                'documentos.'
            );
        } catch (\Exception $e) {
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

            $messages = \App\Support\ArchivosValidation::construirMensajesParaCatalogo(
                $archivosRequeridos,
                'documentos.'
            );
        } catch (\Exception $e) {
        }
        
        return $messages;
    }

    private function validarArchivosRequeridos($validator)
    {
        try {
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
                
                if (!isset($documentos[$nombreCampo]) || !$documentos[$nombreCampo]) {
                    $archivosFaltantes[] = $archivo->nombre;
                }
            }

            if (!empty($archivosFaltantes)) {
                $validator->errors()->add('archivos_faltantes', 'Los siguientes archivos son obligatorios: ' . implode(', ', $archivosFaltantes));
            }
        } catch (\Exception $e) {
            $validator->errors()->add('archivos_error', 'Error al validar archivos: ' . $e->getMessage());
        }
    }

    private function esModoCorreccion(): bool
    {
        $path = $this->path();
        $method = $this->method();
        return str_contains($path, 'edit') || $method === 'PUT';
    }

    private function esPersonaMoral(): bool
    {
        $rfc = $this->input('rfc') ?: $this->input('rfc_hidden') ?: $this->input('rfc_fallback');
        $tipoPersona = $this->input('tipo_persona') ?: $this->input('tipo_persona_hidden') ?: $this->input('tipo_persona_fallback');
        $rfcService = app(\App\Services\RfcProveedorService::class);
        return $rfcService->esPersonaMoral($tipoPersona, $rfc);
    }
}
