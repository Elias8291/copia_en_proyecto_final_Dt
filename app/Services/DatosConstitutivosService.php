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

        // Crear instrumento notarial para datos constitutivos
        $instrumentoNotarial = $this->instrumentoNotarialService->crear($request);
        
        // Si hay instrumento notarial, crear datos constitutivos
        if ($instrumentoNotarial) {
            $this->crearDatosConstitutivos($tramite, $instrumentoNotarial);
        } else {
            Log::info('No se creó instrumento notarial para datos constitutivos', [
                'tramite_id' => $tramite->id
            ]);
        }

        // Crear apoderado legal si existe
        $this->crearApoderadoLegal($tramite, $request);

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
    private function crearApoderadoLegal(Tramite $tramite, TramiteFormularioRequest $request): void
    {
        $apoderadoNombre = $request->input('apoderado_nombre');
        $apoderadoRfc = $request->input('apoderado_rfc');
        
        // Log para debug
        Log::info('Verificando datos de apoderado legal', [
            'tramite_id' => $tramite->id,
            'apoderado_nombre' => $apoderadoNombre,
            'apoderado_rfc' => $apoderadoRfc,
            'nombre_filled' => $request->filled('apoderado_nombre'),
            'rfc_filled' => $request->filled('apoderado_rfc')
        ]);
        
        // Verificar si hay al menos un campo con información
        if (empty($apoderadoNombre) && empty($apoderadoRfc)) {
            Log::info('No hay información de apoderado legal', ['tramite_id' => $tramite->id]);
            return;
        }

        try {
            // Crear instrumento notarial para el poder si hay datos del poder
            $instrumentoNotarialPoder = null;
            if ($this->tieneDatosPoder($request)) {
                $instrumentoNotarialPoder = $this->crearInstrumentoNotarialPoder($request);
            } else {
                // Crear un instrumento notarial por defecto para el apoderado
                $instrumentoNotarialPoder = $this->crearInstrumentoNotarialPorDefecto();
            }
            
            // Asegurar que los campos no sean null
            $nombreApoderado = !empty($apoderadoNombre) ? $apoderadoNombre : 'Apoderado Legal';
            $rfcApoderado = !empty($apoderadoRfc) ? $apoderadoRfc : 'TEMP000000000';
            
            ApoderadoLegal::create([
                'tramite_id' => $tramite->id,
                'instrumento_notarial_id' => $instrumentoNotarialPoder->id,
                'nombre_apoderado' => $nombreApoderado,
                'rfc' => $rfcApoderado,
            ]);

            Log::info('Apoderado legal creado', [
                'tramite_id' => $tramite->id,
                'nombre' => $nombreApoderado,
                'rfc' => $rfcApoderado,
                'instrumento_id' => $instrumentoNotarialPoder->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear apoderado legal', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'datos_intentados' => [
                    'nombre' => $nombreApoderado ?? $apoderadoNombre,
                    'rfc' => $rfcApoderado ?? $apoderadoRfc
                ]
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

    /**
     * Verifica si hay datos del poder notarial
     */
    private function tieneDatosPoder(TramiteFormularioRequest $request): bool
    {
        return $request->filled(['poder_numero_escritura', 'poder_notario_nombre']);
    }
    
    /**
     * Crea un instrumento notarial para el poder
     */
    private function crearInstrumentoNotarialPoder(TramiteFormularioRequest $request): InstrumentoNotarial
    {
        return InstrumentoNotarial::create([
            'numero_escritura' => $request->input('poder_numero_escritura'),
            'numero_escritura_constitutiva' => $request->input('poder_numero_escritura'),
            'fecha_constitucion' => $request->input('poder_fecha_constitucion', now()->toDateString()),
            'nombre_notario' => $request->input('poder_notario_nombre'),
            'entidad_federativa' => $request->input('poder_entidad_federativa', 'Puebla'),
            'numero_notario' => (int) $request->input('poder_notario_numero', 1),
            'numero_registro_publico' => $request->input('poder_numero_registro', 'N/A'),
            'fecha_inscripcion' => $request->input('poder_fecha_constitucion', now()->toDateString()),
        ]);
    }
    
    /**
     * Crea un instrumento notarial por defecto para el apoderado
     */
    private function crearInstrumentoNotarialPorDefecto(): InstrumentoNotarial
    {
        return InstrumentoNotarial::create([
            'numero_escritura' => 'PODER-APODERADO-' . time(),
            'numero_escritura_constitutiva' => 'PODER-APODERADO-' . time(),
            'fecha_constitucion' => now()->toDateString(),
            'nombre_notario' => 'Notario por Defecto',
            'entidad_federativa' => 'Puebla',
            'numero_notario' => 1,
            'numero_registro_publico' => 'N/A',
            'fecha_inscripcion' => now()->toDateString(),
        ]);
    }
} 