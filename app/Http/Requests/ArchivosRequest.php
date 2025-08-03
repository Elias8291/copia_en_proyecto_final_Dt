<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchivosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'documentos.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg,mp3,mp4|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'documentos.*.file' => 'El archivo debe ser válido.',
            'documentos.*.mimes' => 'El archivo debe ser PDF, PNG, JPG, MP3 o MP4.',
            'documentos.*.max' => 'El archivo no puede ser mayor a 50MB.',
        ];
    }
} 