<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\InstrumentoNotarial;
use App\Models\DatosConstitutivos;
use App\Models\ApoderadoLegal;
use App\Models\Accionista;
use App\Http\Requests\TramiteFormularioRequest;
use Illuminate\Support\Facades\Log;

class DatosConstitutivosService
{
    public function __construct(
        private InstrumentoNotarialService $instrumentoNotarialService
    ) {}

    /**
     * Procesa todos los datos constitutivos de persona moral
     */
    public function procesar(Tramite $tramite, TramiteFormularioRequest $request): void
    {
        Log::info('Procesando datos constitutivos', ['tramite_id' => $tramite->id]);

        // Crear instrumento notarial
        $instrumentoNotarial = $this->instrumentoNotarialService->crear($request);
        if (!$instrumentoNotarial) {
            Log::info('No se creó instrumento notarial, omitiendo datos constitutivos', [
                'tramite_id' => $tramite->id
            ]);
            return;
        }

        // Crear registro de datos constitutivos
        $this->crearDatosConstitutivos($tramite, $instrumentoNotarial);

        // Crear apoderado legal si existe
        $this->crearApoderadoLegal($tramite, $instrumentoNotarial, $request);

        // Crear accionistas si existen
        $this->crearAccionistas($tramite, $request);
    }

    /**
     * Crea el registro de datos constitutivos
     */
    private function crearDatosConstitutivos(Tramite $tramite, InstrumentoNotarial $instrumentoNotarial): void
    {
        try {
            DatosConstitutivos::create([
                'tramite_id' => $tramite->id,
                'instrumento_notarial_id' => $instrumentoNotarial->id,
            ]);

            Log::info('Datos constitutivos vinculados', [
                'tramite_id' => $tramite->id,
                'instrumento_id' => $instrumentoNotarial->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear datos constitutivos', [
                'tramite_id' => $tramite->id,
                'instrumento_id' => $instrumentoNotarial->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Crea el apoderado legal si existe información
     */
    private function crearApoderadoLegal(Tramite $tramite, InstrumentoNotarial $instrumentoNotarial, TramiteFormularioRequest $request): void
    {
        if (!$request->filled(['apoderado_nombre', 'apoderado_rfc'])) {
            Log::info('No hay información de apoderado legal', ['tramite_id' => $tramite->id]);
            return;
        }

        try {
            ApoderadoLegal::create([
                'tramite_id' => $tramite->id,
                'instrumento_notarial_id' => $instrumentoNotarial->id,
                'nombre_apoderado' => $request->input('apoderado_nombre'),
                'rfc' => $request->input('apoderado_rfc'),
            ]);

            Log::info('Apoderado legal creado', [
                'tramite_id' => $tramite->id,
                'nombre' => $request->input('apoderado_nombre')
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear apoderado legal', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Crea los accionistas si existen
     */
    private function crearAccionistas(Tramite $tramite, TramiteFormularioRequest $request): void
    {
        $accionistas = $request->input('accionistas', []);
        if (empty($accionistas) || !is_array($accionistas)) {
            Log::info('No hay accionistas para procesar', ['tramite_id' => $tramite->id]);
            return;
        }

        $accionistasCreados = 0;
        foreach ($accionistas as $index => $datos) {
            if ($this->validarDatosAccionista($datos)) {
                if ($this->crearAccionista($tramite, $datos, $index)) {
                    $accionistasCreados++;
                }
            }
        }

        Log::info('Procesamiento de accionistas completado', [
            'tramite_id' => $tramite->id,
            'total_enviados' => count($accionistas),
            'total_creados' => $accionistasCreados
        ]);
    }

    /**
     * Valida los datos básicos de un accionista
     */
    private function validarDatosAccionista(array $datos): bool
    {
        return !empty($datos['nombre']) || !empty($datos['rfc']);
    }

    /**
     * Crea un accionista individual
     */
    private function crearAccionista(Tramite $tramite, array $datos, int $index): bool
    {
        try {
            Accionista::create([
                'tramite_id' => $tramite->id,
                'nombre_completo' => $datos['nombre'] ?? "Accionista #{$index}",
                'rfc' => $datos['rfc'] ?? null,
                'porcentaje_participacion' => (float) ($datos['porcentaje'] ?? 0),
            ]);

            Log::info("Accionista #{$index} creado", [
                'tramite_id' => $tramite->id,
                'nombre' => $datos['nombre'] ?? "Accionista #{$index}"
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Error al crear accionista #{$index}", [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'datos' => $datos
            ]);

            return false;
        }
    }
} 