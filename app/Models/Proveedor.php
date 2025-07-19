<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'user_id',
        'rfc',
        'curp',
        'tipo_persona',
        'razon_social',
        'pv_numero',
        'estado_padron',
        'fecha_alta_padron',
        'fecha_vencimiento_padron',
    ];

    protected $casts = [
        'fecha_alta_padron' => 'date',
        'fecha_vencimiento_padron' => 'date',
    ];

    /**
     * Relación: usuario del proveedor
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determinar tipo de persona basado en RFC
     */
    public static function determineTipoPersona(string $rfc): string
    {
        return strlen($rfc) === 12 ? 'Física' : 'Moral';
    }

    /**
     * Obtener los días totales de vigencia del proveedor
     * (desde fecha de alta hasta fecha de vencimiento)
     */
    public function getDiasVigenciaTotal(): ?int
    {
        if (!$this->fecha_alta_padron || !$this->fecha_vencimiento_padron) {
            return null;
        }

        return $this->fecha_alta_padron->diffInDays($this->fecha_vencimiento_padron);
    }

    /**
     * Obtener los días restantes de vigencia del proveedor
     * (desde hoy hasta fecha de vencimiento)
     */
    public function getDiasRestantesVigencia(): ?int
    {
        if (!$this->fecha_vencimiento_padron) {
            return null;
        }

        $diasRestantes = now()->diffInDays($this->fecha_vencimiento_padron, false);
        return $diasRestantes >= 0 ? $diasRestantes : 0;
    }

    /**
     * Obtener los días transcurridos desde el alta del proveedor
     */
    public function getDiasDesdeAlta(): ?int
    {
        if (!$this->fecha_alta_padron) {
            return null;
        }

        return $this->fecha_alta_padron->diffInDays(now());
    }

    /**
     * Verificar si el proveedor está en período de renovación (últimos 7 días)
     */
    public function estaEnPeriodoRenovacion(): bool
    {
        if (!$this->fecha_vencimiento_padron || $this->estado_padron !== 'Activo') {
            return false;
        }

        $diasRestantes = $this->getDiasRestantesVigencia();
        return $diasRestantes !== null && $diasRestantes <= 7 && $diasRestantes >= 0;
    }

    /**
     * Verificar si el proveedor ya venció
     */
    public function estaVencido(): bool
    {
        if (!$this->fecha_vencimiento_padron) {
            return false;
        }

        return now()->isAfter($this->fecha_vencimiento_padron);
    }

    /**
     * Obtener el porcentaje de vigencia transcurrida
     */
    public function getPorcentajeVigenciaTranscurrida(): ?float
    {
        $diasTotal = $this->getDiasVigenciaTotal();
        $diasTranscurridos = $this->getDiasDesdeAlta();

        if (!$diasTotal || !$diasTranscurridos || $diasTotal <= 0) {
            return null;
        }

        $porcentaje = ($diasTranscurridos / $diasTotal) * 100;
        return min(100, max(0, $porcentaje)); // Limitar entre 0 y 100
    }

    /**
     * Obtener el estado de la vigencia en formato legible
     */
    public function getEstadoVigencia(): array
    {
        if (!$this->fecha_vencimiento_padron) {
            return [
                'tipo' => 'sin_fecha',
                'mensaje' => 'Sin fecha de vencimiento definida',
                'clase_css' => 'text-gray-500'
            ];
        }

        $diasRestantes = $this->getDiasRestantesVigencia();

        if ($this->estaVencido()) {
            return [
                'tipo' => 'vencido',
                'mensaje' => 'Registro vencido',
                'dias' => 0,
                'clase_css' => 'text-red-600'
            ];
        }

        if ($this->estaEnPeriodoRenovacion()) {
            return [
                'tipo' => 'renovacion',
                'mensaje' => "Vence en {$diasRestantes} día(s) - Puede renovar",
                'dias' => $diasRestantes,
                'clase_css' => 'text-amber-600'
            ];
        }

        return [
            'tipo' => 'vigente',
            'mensaje' => "Vigente - {$diasRestantes} día(s) restantes",
            'dias' => $diasRestantes,
            'clase_css' => 'text-green-600'
        ];
    }
}
