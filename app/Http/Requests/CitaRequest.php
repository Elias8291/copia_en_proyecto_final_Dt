<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Cita;

class CitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'tramite_id' => 'required|exists:tramites,id',
            'tipo_cita' => 'required|in:Digital,Presencial,Domiciliaria',
            'estado' => 'required|in:Asignada,Cancelada,Asistida,No_Asistio',
            'asignado_a' => 'nullable|exists:users,id'
        ];

        // Para crear: fecha debe ser futura
        if ($this->isMethod('post')) {
            $rules['fecha_cita'] = 'required|date|after:now';
        } 
        // Para editar: fecha puede ser en el pasado
        else {
            $rules['fecha_cita'] = 'required|date';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'tramite_id.required' => 'Debe seleccionar un trámite.',
            'tramite_id.exists' => 'El trámite seleccionado no existe.',
            'tipo_cita.required' => 'Debe seleccionar el tipo de cita.',
            'tipo_cita.in' => 'El tipo de cita debe ser Digital, Presencial o Domiciliaria.',
            'fecha_cita.required' => 'La fecha de la cita es obligatoria.',
            'fecha_cita.date' => 'Debe ingresar una fecha válida.',
            'fecha_cita.after' => 'La fecha de la cita debe ser posterior a la fecha actual.',
            'estado.required' => 'Debe seleccionar el estado de la cita.',
            'estado.in' => 'El estado debe ser Asignada, Cancelada, Asistida o No Asistió.',
            'asignado_a.exists' => 'El usuario asignado no existe.'
        ];
    }

    public function attributes(): array
    {
        return [
            'tramite_id' => 'trámite',
            'tipo_cita' => 'tipo de cita',
            'fecha_cita' => 'fecha de cita',
            'estado' => 'estado',
            'asignado_a' => 'asignado a'
        ];
    }

    protected function prepareForValidation()
    {
        // Convertir fecha si viene en formato español
        if ($this->has('fecha_cita') && $this->fecha_cita) {
            $fecha = $this->fecha_cita;
            
            // Si viene en formato español d/m/Y H:i, convertir a Y-m-d H:i
            if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}$/', $fecha)) {
                $fechaConvertida = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $fecha)->format('Y-m-d H:i');
                $this->merge(['fecha_cita' => $fechaConvertida]);
            }
        }
    }
}
