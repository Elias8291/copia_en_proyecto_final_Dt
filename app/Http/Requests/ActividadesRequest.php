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
            'actividades' => 'required|array|min:1',
            'actividades.*' => 'exists:actividades,id',
            'actividades_seleccionadas' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'actividades.required' => 'Debe seleccionar al menos una actividad económica.',
            'actividades.array' => 'Las actividades deben ser una lista válida.',
            'actividades.min' => 'Debe seleccionar al menos una actividad económica.',
            'actividades.*.exists' => 'Una de las actividades seleccionadas no es válida.',
        ];
    }
} 