<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id;
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId)
            ],
            'description' => 'nullable|string|max:500',
            'guard_name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.string' => 'El nombre del rol debe ser una cadena de texto.',
            'name.max' => 'El nombre del rol no puede tener más de 255 caracteres.',
            'name.unique' => 'Ya existe un rol con este nombre.',
            
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 500 caracteres.',
            
            'guard_name.required' => 'El guard es obligatorio.',
            'guard_name.string' => 'El guard debe ser una cadena de texto.',
            'guard_name.max' => 'El guard no puede tener más de 255 caracteres.',
            
            'permissions.array' => 'Los permisos deben ser una lista.',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no son válidos.'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre del rol',
            'description' => 'descripción',
            'guard_name' => 'guard',
            'permissions' => 'permisos'
        ];
    }
} 