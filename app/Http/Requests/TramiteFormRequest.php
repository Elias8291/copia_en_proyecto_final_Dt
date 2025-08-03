<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $archivosRequest = new ArchivosRequest();

        $rules = array_merge(
            $rules,
            $datosGeneralesRequest->rules(),
            $domicilioRequest->rules(),
            $actividadesRequest->rules()
        );

        // Solo agregar validación de archivos si se suben archivos
        if ($this->hasFile('documentos')) {
            $rules = array_merge($rules, $archivosRequest->rules());
        }

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
        $archivosRequest = new ArchivosRequest();

        $messages = array_merge(
            $messages,
            $datosGeneralesRequest->messages(),
            $domicilioRequest->messages(),
            $actividadesRequest->messages()
        );

        // Solo agregar mensajes de archivos si se suben archivos
        if ($this->hasFile('documentos')) {
            $messages = array_merge($messages, $archivosRequest->messages());
        }

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

    private function esPersonaMoral(): bool
    {
        $rfc = $this->input('rfc');
        return $rfc && strlen($rfc) === 12;
    }
}
