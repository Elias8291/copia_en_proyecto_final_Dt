<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo,NULL,id,deleted_at,NULL',
            'rfc' => 'nullable|string|max:13|unique:users,rfc,NULL,id,deleted_at,NULL',
            'password' => 'required|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name'
        ];
    }

    /**
     * Get custom validation messages in Spanish
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'rfc.string' => 'El RFC debe ser una cadena de texto.',
            'rfc.max' => 'El RFC no puede tener más de 13 caracteres.',
            'rfc.unique' => 'Este RFC ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'roles.array' => 'Los roles deben ser una lista.',
            'roles.*.exists' => 'Uno de los roles seleccionados no existe.'
        ];
    }

    /**
     * Configure the validator instance with custom RFC validation
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->rfc) {
                $rfc = strtoupper(trim($this->rfc));
                
                // Validar formato básico de RFC
                if (!preg_match('/^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfc)) {
                    $validator->errors()->add('rfc', 'El RFC debe tener un formato válido.');
                }

                // Verificar duplicados excluyendo usuarios soft-deleted
                if (User::where('rfc', $rfc)->whereNull('deleted_at')->exists()) {
                    $validator->errors()->add('rfc', 'Ya existe un usuario registrado con este RFC.');
                }
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->rfc) {
            $this->merge([
                'rfc' => strtoupper(trim($this->rfc))
            ]);
        }
    }
} 