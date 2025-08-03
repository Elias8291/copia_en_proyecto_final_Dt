<?php

namespace App\Services\Tramites;

use Illuminate\Http\Request;

class ConstanciaService
{
    public function procesar(Request $request): array
    {
        $file = $request->file('document');
        $fileName = 'constancia_' . time() . '_' . auth()->id() . '.pdf';
        $path = $file->storeAs('constancias', $fileName, 'public');

        return [
            'constancia_path' => $path,
            'constancia_name' => $file->getClientOriginalName(),
            'constancia_uploaded' => true,
            'sat_rfc' => $request->sat_rfc,
            'sat_nombre' => $request->sat_nombre,
            'sat_tipo_persona' => $request->sat_tipo_persona,
            'sat_email' => $request->sat_email,
            'sat_curp' => $request->sat_curp,
            'qr_url' => $request->qr_url,
            'sat_calle' => $request->sat_calle,
            'sat_numero_exterior' => $request->sat_numero_exterior,
            'sat_numero_interior' => $request->sat_numero_interior,
            'sat_colonia' => $request->sat_colonia,
            'sat_cp' => $request->sat_cp,
            'sat_municipio' => $request->sat_municipio,
            'sat_entidad_federativa' => $request->sat_entidad_federativa,
        ];
    }

    public function validarRfcUsuario(string $rfcConstancia): bool
    {
        $usuario = auth()->user();
        
        if (!$usuario) {
            return false;
        }

        $rfcUsuario = $usuario->rfc ?? '';
        
        if (empty($rfcUsuario)) {
            return true;
        }

        return strtoupper(trim($rfcConstancia)) === strtoupper(trim($rfcUsuario));
    }

    public function obtenerDatos(): array
    {
        return [
            'rfc' => session('sat_rfc'),
            'razon_social' => session('sat_nombre'),
            'curp' => session('sat_curp'),
            'tipo_persona' => session('sat_tipo_persona'),
            'email' => session('sat_email'),
            'domicilio' => [
                'calle' => session('sat_calle'),
                'numero_exterior' => session('sat_numero_exterior'),
                'numero_interior' => session('sat_numero_interior'),
                'colonia' => session('sat_colonia'),
                'codigo_postal' => session('sat_cp'),
                'municipio' => session('sat_municipio'),
                'entidad_federativa' => session('sat_entidad_federativa'),
            ]
        ];
    }

    public function verificarCargada(): bool
    {
        return session('constancia_uploaded', false);
    }

    public function determinarTipoPersona(string $rfc): string
    {
        return strlen($rfc) === 12 ? 'Moral' : 'Física';
    }
} 