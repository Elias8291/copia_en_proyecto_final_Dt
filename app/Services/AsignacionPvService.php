<?php

namespace App\Services;

use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsignacionPvService
{
    /**
     * Asigna PV y fechas de vigencia para un proveedor en inscripción
     * 
     * @param int $numeroProveedor Identificador numérico del proveedor
     * @param string|null $ultimoPvSistema Último PV existente en el sistema (formato PV901323)
     * @param string $fechaRevision Fecha de revisión en formato ISO (YYYY-MM-DD)
     * @return array JSON con los datos asignados
     */
    public function asignarPvYVigencia(int $numeroProveedor, ?string $ultimoPvSistema, string $fechaRevision): array
    {
        $notasValidacion = [];
        
        try {
            // Validar formato de fecha de revisión
            if (!$this->validarFormatoFecha($fechaRevision)) {
                return $this->generarRespuestaError('Formato de fecha inválido. Debe ser YYYY-MM-DD');
            }
            
            $fechaRevisionCarbon = Carbon::parse($fechaRevision);
            
            // Generar PV según reglas
            $pv = $this->generarPv($numeroProveedor, $ultimoPvSistema, $notasValidacion);
            
            if (!$pv) {
                return $this->generarRespuestaError('Error al generar PV: ' . implode(', ', $notasValidacion));
            }
            
            // Calcular fechas de vigencia
            $vigenciaInicio = $fechaRevisionCarbon->format('Y-m-d');
            $vigenciaFin = $this->calcularFechaVencimiento($fechaRevisionCarbon)->format('Y-m-d');
            
            // Verificar que vigencia_inicio < vigencia_fin
            if ($vigenciaInicio >= $vigenciaFin) {
                return $this->generarRespuestaError('Error en fechas de vigencia: inicio debe ser menor que fin');
            }
            
            // Verificar unicidad del PV
            if (!$this->verificarUnicidadPv($pv)) {
                $notasValidacion[] = 'PV generado ya existe, se requiere regeneración';
                return $this->generarRespuestaError('PV no es único en el sistema');
            }
            
            return [
                'pv' => $pv,
                'vigencia_inicio' => $vigenciaInicio,
                'vigencia_fin' => $vigenciaFin,
                'fecha_alta_padron' => $vigenciaInicio, // Solo en inscripción
                'notas_validacion' => implode('; ', $notasValidacion)
            ];
            
        } catch (\Exception $e) {
            Log::error('Error en AsignacionPvService::asignarPvYVigencia', [
                'numero_proveedor' => $numeroProveedor,
                'ultimo_pv_sistema' => $ultimoPvSistema,
                'fecha_revision' => $fechaRevision,
                'error' => $e->getMessage()
            ]);
            
            return $this->generarRespuestaError('Error interno del sistema: ' . $e->getMessage());
        }
    }
    
    /**
     * Genera el PV según las reglas de negocio
     */
    private function generarPv(int $numeroProveedor, ?string $ultimoPvSistema, array &$notasValidacion): ?string
    {
        // Validar formato del último PV del sistema
        if ($ultimoPvSistema && !preg_match('/^PV\d+$/', $ultimoPvSistema)) {
            $notasValidacion[] = 'Formato de último PV inválido, se ignora';
            $ultimoPvSistema = null;
        }
        
        if ($ultimoPvSistema) {
            // Extraer correlativo base del último PV
            $correlativoBase = substr($ultimoPvSistema, 2); // Remover "PV"
            
            // Construir nuevo PV: PV + correlativo_base + numero_proveedor
            $pv = 'PV' . $correlativoBase . $numeroProveedor;
            $notasValidacion[] = 'PV generado con correlativo base: ' . $correlativoBase;
        } else {
            // Si no hay último PV, asignar: PV + numero_proveedor
            $pv = 'PV' . $numeroProveedor;
            $notasValidacion[] = 'PV generado sin correlativo base';
        }
        
        // Verificar formato del PV generado
        if (!preg_match('/^PV\d+$/', $pv)) {
            $notasValidacion[] = 'Formato de PV generado inválido';
            return null;
        }
        
        return $pv;
    }
    
    /**
     * Calcula fecha de vencimiento (1 año exacto considerando años bisiestos)
     */
    private function calcularFechaVencimiento(Carbon $fechaInicio): Carbon
    {
        $fechaVencimiento = $fechaInicio->copy()->addYear();
        
        // Regla de bisiesto: si la fecha es 29-02 y el año siguiente no es bisiesto, usar 28-02
        if ($fechaInicio->month === 2 && $fechaInicio->day === 29) {
            if (!$fechaVencimiento->isLeapYear()) {
                $fechaVencimiento = $fechaVencimiento->setDay(28);
            }
        }
        
        return $fechaVencimiento;
    }
    
    /**
     * Verifica que el PV sea único en el sistema
     */
    private function verificarUnicidadPv(string $pv): bool
    {
        return !Proveedor::where('pv_numero', $pv)->exists();
    }
    
    /**
     * Valida formato de fecha ISO
     */
    private function validarFormatoFecha(string $fecha): bool
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) && 
               Carbon::canParse($fecha);
    }
    
    /**
     * Genera respuesta de error
     */
    private function generarRespuestaError(string $mensaje): array
    {
        return [
            'pv' => null,
            'vigencia_inicio' => null,
            'vigencia_fin' => null,
            'fecha_alta_padron' => null,
            'notas_validacion' => $mensaje
        ];
    }
    
    /**
     * Aplica la asignación a un proveedor existente
     * 
     * @param Proveedor $proveedor
     * @param array $datosAsignacion Datos retornados por asignarPvYVigencia
     * @return bool
     */
    public function aplicarAsignacion(Proveedor $proveedor, array $datosAsignacion): bool
    {
        try {
            DB::beginTransaction();
            
            $actualizacion = [
                'pv_numero' => $datosAsignacion['pv'],
                'estado_padron' => 'Activo'
            ];
            
            // Solo asignar fecha_alta_padron si no existe
            if (!$proveedor->fecha_alta_padron) {
                $actualizacion['fecha_alta_padron'] = $datosAsignacion['fecha_alta_padron'];
            } else {
                // Anotar en log si ya existía
                Log::info('Proveedor ya tenía fecha_alta_padron', [
                    'proveedor_id' => $proveedor->id,
                    'fecha_existente' => $proveedor->fecha_alta_padron,
                    'fecha_nueva' => $datosAsignacion['fecha_alta_padron']
                ]);
            }
            
            // Actualizar fechas de vigencia
            $actualizacion['fecha_vencimiento_padron'] = $datosAsignacion['vigencia_fin'];
            
            $proveedor->update($actualizacion);
            
            DB::commit();
            
            Log::info('Asignación de PV aplicada exitosamente', [
                'proveedor_id' => $proveedor->id,
                'pv_asignado' => $datosAsignacion['pv'],
                'vigencia_inicio' => $datosAsignacion['vigencia_inicio'],
                'vigencia_fin' => $datosAsignacion['vigencia_fin']
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al aplicar asignación de PV', [
                'proveedor_id' => $proveedor->id,
                'datos_asignacion' => $datosAsignacion,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
