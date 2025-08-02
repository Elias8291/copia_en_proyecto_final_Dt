<?php

declare(strict_types=1);

namespace App\Services\Tramites;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Servicio para manejar datos SAT en sesión
 * Responsabilidad: Gestionar datos de la constancia SAT en la sesión del usuario
 */
class SesionSatService
{
    private const CLAVES_SESION_SAT = [
        'sat_rfc', 'sat_nombre', 'sat_tipo_persona', 'sat_curp',
        'sat_cp', 'sat_colonia', 'sat_nombre_vialidad',
        'sat_numero_exterior', 'sat_numero_interior'
    ];

    /**
     * Extrae los datos SAT de la petición
     */
    public function extraerDatosDePeticion(Request $request): array
    {
        $datosSat = [];
        foreach (self::CLAVES_SESION_SAT as $clave) {
            $datosSat[$clave] = $request->input($clave);
        }
        return $datosSat;
    }

    /**
     * Guarda los datos SAT en la sesión
     */
    public function guardar(array $datosSat): void
    {
        Session::put($datosSat);
    }

    /**
     * Limpia todos los datos SAT de la sesión
     */
    public function limpiar(): void
    {
        foreach (self::CLAVES_SESION_SAT as $clave) {
            Session::forget($clave);
        }
    }

    /**
     * Obtiene todos los datos SAT de la sesión
     */
    public function obtener(): array
    {
        return [
            'rfc' => Session::get('sat_rfc'),
            'razon_social' => Session::get('sat_nombre'),
            'tipo_persona' => Session::get('sat_tipo_persona'),
            'curp' => Session::get('sat_curp'),
            'cp' => Session::get('sat_cp'),
            'colonia' => Session::get('sat_colonia'),
            'nombre_vialidad' => Session::get('sat_nombre_vialidad'),
            'numero_exterior' => Session::get('sat_numero_exterior'),
            'numero_interior' => Session::get('sat_numero_interior'),
        ];
    }

    /**
     * Verifica si existen datos SAT en la sesión
     */
    public function tieneDatos(): bool
    {
        return Session::has('sat_rfc') && !empty(Session::get('sat_rfc'));
    }

    /**
     * Obtiene un dato específico de la sesión SAT
     */
    public function obtenerDatoEspecifico(string $clave): mixed
    {
        return Session::get($clave);
    }

    // Métodos de compatibilidad (deprecated - usar los métodos sin "Datos")
    public function guardarDatos(array $datosSat): void
    {
        $this->guardar($datosSat);
    }

    public function limpiarDatos(): void
    {
        $this->limpiar();
    }

    public function obtenerDatos(): array
    {
        return $this->obtener();
    }
}