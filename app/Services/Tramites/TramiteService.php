<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use Illuminate\Support\Facades\DB;

class TramiteService
{
    private ConstanciaService $constanciaService;

    public function __construct(ConstanciaService $constanciaService)
    {
        $this->constanciaService = $constanciaService;
    }

    public function crear(array $datos): Tramite
    {
        return DB::transaction(function () use ($datos) {
            $tramite = Tramite::create([
                'user_id' => auth()->id(),
                'constancia_path' => session('constancia_path'),
                'constancia_name' => session('constancia_name'),
            ]);

            $this->crearDatosGenerales($tramite, $datos);
            $this->crearDomicilio($tramite, $datos);
            $this->crearActividades($tramite, $datos);
            $this->crearAccionistas($tramite, $datos);
            $this->crearApoderado($tramite, $datos);
            $this->procesarArchivos($tramite, $datos);

            return $tramite;
        });
    }

    public function verificarConstanciaCargada(): bool
    {
        return $this->constanciaService->verificarCargada();
    }

    public function obtenerDatosConstancia(): array
    {
        return $this->constanciaService->obtenerDatos();
    }

    private function crearDatosGenerales(Tramite $tramite, array $datos): void
    {
        $tramite->datosGenerales()->create([
            'razon_social' => $datos['razon_social'],
            'rfc' => $datos['rfc'],
            'tipo_persona' => $datos['tipo_persona'],
            'curp' => $datos['curp'],
            'pagina_web' => $datos['pagina_web'] ?? null,
            'telefono' => $datos['telefono'],
            'correo_electronico' => $datos['correo_electronico'] ?? null,
        ]);
    }

    private function crearDomicilio(Tramite $tramite, array $datos): void
    {
        $tramite->domicilio()->create([
            'codigo_postal' => $datos['codigo_postal'],
            'estado_id' => $datos['estado_id'],
            'municipio' => $datos['municipio'],
            'asentamiento' => $datos['asentamiento'],
            'calle' => $datos['calle'],
            'entre_calle' => $datos['entre_calle'] ?? null,
            'y_calle' => $datos['y_calle'] ?? null,
            'numero_exterior' => $datos['numero_exterior'],
            'numero_interior' => $datos['numero_interior'] ?? null,
            'latitud' => $datos['latitud'] ?? null,
            'longitud' => $datos['longitud'] ?? null,
        ]);
    }

    private function crearActividades(Tramite $tramite, array $datos): void
    {
        if (!empty($datos['actividades'])) {
            foreach ($datos['actividades'] as $actividadId) {
                $tramite->actividades()->attach($actividadId);
            }
        }
    }

    private function crearAccionistas(Tramite $tramite, array $datos): void
    {
        if (!empty($datos['accionistas'])) {
            foreach ($datos['accionistas'] as $accionista) {
                $tramite->accionistas()->create([
                    'nombre' => $accionista['nombre'],
                    'rfc' => $accionista['rfc'],
                    'porcentaje_participacion' => $accionista['porcentaje_participacion'],
                ]);
            }
        }
    }

    private function crearApoderado(Tramite $tramite, array $datos): void
    {
        if (!empty($datos['apoderado'])) {
            $tramite->apoderadoLegal()->create([
                'nombre' => $datos['apoderado']['nombre'],
                'rfc' => $datos['apoderado']['rfc'],
                'numero_escritura_constitutiva_poder' => $datos['apoderado']['numero_escritura_constitutiva_poder'] ?? null,
                'numero_registro_publico_poder' => $datos['apoderado']['numero_registro_publico_poder'] ?? null,
                'fecha_inscripcion_poder' => $datos['apoderado']['fecha_inscripcion_poder'] ?? null,
            ]);
        }
    }

    private function procesarArchivos(Tramite $tramite, array $datos): void
    {
        if (!empty($datos['documentos'])) {
            foreach ($datos['documentos'] as $tipo => $archivo) {
                if ($archivo && $archivo->isValid()) {
                    $fileName = $tipo . '_' . time() . '_' . auth()->id() . '.' . $archivo->getClientOriginalExtension();
                    $path = $archivo->storeAs('documentos', $fileName, 'public');
                    
                    $tramite->archivos()->create([
                        'tipo' => $tipo,
                        'nombre' => $archivo->getClientOriginalName(),
                        'ruta' => $path,
                        'tamaño' => $archivo->getSize(),
                    ]);
                }
            }
        }
    }
} 