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
        // $archivosRequest = new ArchivosRequest();

        $rules = array_merge(
            $rules,
            $datosGeneralesRequest->rules(),
            $domicilioRequest->rules(),
            $actividadesRequest->rules()
            // $archivosRequest->rules()
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

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        $datosGeneralesRequest = new DatosGeneralesRequest();
        $domicilioRequest = new DomicilioRequest();
        $actividadesRequest = new ActividadesRequest();
        // $archivosRequest = new ArchivosRequest();

        $messages = array_merge(
            $messages,
            $datosGeneralesRequest->messages(),
            $domicilioRequest->messages(),
            $actividadesRequest->messages()
            // $archivosRequest->messages()
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
            
            // Comentar temporalmente la validación de archivos para pruebas
            // $this->validarArchivosRequeridos($validator);
        });
    }

    private function validarArchivosRequeridos($validator)
    {
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
    }

    private function esPersonaMoral(): bool
    {
        $rfc = $this->input('rfc') ?: $this->input('rfc_hidden');
        $tipoPersona = $this->input('tipo_persona') ?: $this->input('tipo_persona_hidden');
        
        // Si tenemos tipo_persona, usarlo directamente
        if ($tipoPersona === 'Moral') {
            return true;
        }
        
        // Si no, usar el RFC con la lógica del servicio
        if ($rfc) {
            $rfcService = app(\App\Services\RfcProveedorService::class);
            return $rfcService->determinarTipoPersona($rfc) === 'Moral';
        }
        
        return false;
    }
}
