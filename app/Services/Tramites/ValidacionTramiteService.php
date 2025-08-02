<?php

declare(strict_types=1);

namespace App\Services\Tramites;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio para validaciones específicas de trámites
 * Responsabilidad: Centralizar todas las validaciones relacionadas con trámites
 */
class ValidacionTramiteService
{
    /**
     * Valida que el RFC de la constancia coincida con el RFC del usuario
     */
    public function validarRfcConstancia(array $datosSat): void
    {
        $rfcUsuario = Auth::user()->rfc;
        $rfcConstancia = $datosSat['sat_rfc'] ?? null;

        if ($rfcUsuario && $rfcConstancia) {
            $rfcUsuarioNormalizado = strtoupper(trim($rfcUsuario));
            $rfcConstanciaNormalizado = strtoupper(trim($rfcConstancia));

            if ($rfcUsuarioNormalizado !== $rfcConstanciaNormalizado) {
                throw new \Exception('El RFC de la constancia fiscal no coincide con su RFC registrado. Verifique que esté cargando la constancia correcta.');
            }
        } else {
            if (!$rfcUsuario) {
                throw new \Exception('Su cuenta no tiene un RFC registrado. Contacte al administrador.');
            }
            if (!$rfcConstancia) {
                throw new \Exception('No se pudo extraer el RFC de la constancia. Verifique que el archivo sea válido.');
            }
        }
    }

    /**
     * Normaliza un RFC eliminando espacios y convirtiéndolo a mayúsculas
     */
    public function normalizarRfc(?string $rfc): string
    {
        if (!empty($rfc) && strlen($rfc) >= 10) {
            return strtoupper(trim($rfc));
        }

        return 'TEMP' . substr((string) time(), -6);
    }

    /**
     * Determina si un RFC corresponde a una persona moral
     */
    public function esPersonaMoral(string $rfc): bool
    {
        // RFC de 12 caracteres = Persona Moral
        // RFC de 13 caracteres = Persona Física
        return strlen(trim($rfc)) === 12;
    }

    /**
     * Valida el acceso a un tipo de trámite específico
     */
    public function validarAccesoTramite(string $tipo, ?Proveedor $proveedor, array $tramitesDisponibles): bool
    {
        if (app()->environment('local', 'development')) {
            return in_array($tipo, ['inscripcion', 'renovacion', 'actualizacion']);
        }
        
        return $tramitesDisponibles[$tipo] ?? false;
    }

    /**
     * Valida que los datos requeridos estén presentes en la petición
     */
    public function validarDatosRequeridos(array $datos, array $camposRequeridos): array
    {
        $errores = [];
        
        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo])) {
                $errores[] = "El campo {$campo} es requerido.";
            }
        }
        
        return $errores;
    }
}