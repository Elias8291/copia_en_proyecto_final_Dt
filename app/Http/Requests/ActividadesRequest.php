<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'actividades_seleccionadas' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'actividades_seleccionadas.required' => 'Debe seleccionar al menos una actividad económica.',
            'actividades_seleccionadas.string' => 'El formato de actividades no es válido.',
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $actividadesSeleccionadas = $this->input('actividades_seleccionadas');
            
            if ($actividadesSeleccionadas) {
                $actividadesArray = json_decode($actividadesSeleccionadas, true);
                
                if (!is_array($actividadesArray) || empty($actividadesArray)) {
                    $validator->errors()->add('actividades', 'Debe seleccionar al menos una actividad económica.');
                }
            }
        });
    }
} 