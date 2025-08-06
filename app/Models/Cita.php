<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Cita extends Model
{
    protected $table = 'citas';
    
    const TIPO_DIGITAL = 'Digital';
    const TIPO_PRESENCIAL = 'Presencial';
    const TIPO_DOMICILIARIA = 'Domiciliaria';
    
    const ESTADO_ASIGNADA = 'Asignada';
    const ESTADO_CANCELADA = 'Cancelada';
    const ESTADO_ASISTIDA = 'Asistida';
    const ESTADO_NO_ASISTIO = 'No_Asistio';
    
    protected $fillable = [
        'tramite_id',
        'tipo_cita',
        'fecha_cita',
        'estado',
        'intento',
        'asignado_a'
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
        'intento' => 'integer'
    ];

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    public function asignadoA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    public function usuario()
    {
        return $this->tramite->proveedor->usuario ?? null;
    }

    public function getUsuarioAttribute()
    {
        return $this->tramite?->proveedor?->usuario;
    }

    public function getProveedorAttribute()
    {
        return $this->tramite?->proveedor;
    }

    public static function getTiposCita(): array
    {
        return [
            self::TIPO_DIGITAL => 'Digital',
            self::TIPO_PRESENCIAL => 'Presencial',
            self::TIPO_DOMICILIARIA => 'Domiciliaria'
        ];
    }

    public static function getEstados(): array
    {
        return [
            self::ESTADO_ASIGNADA => 'Asignada',
            self::ESTADO_CANCELADA => 'Cancelada',
            self::ESTADO_ASISTIDA => 'Asistida',
            self::ESTADO_NO_ASISTIO => 'No Asistió'
        ];
    }

    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            self::ESTADO_ASIGNADA => 'Asignada',
            self::ESTADO_CANCELADA => 'Cancelada',
            self::ESTADO_ASISTIDA => 'Asistida',
            self::ESTADO_NO_ASISTIO => 'No Asistió',
            default => $this->estado
        };
    }

    public function getTipoCitaLabelAttribute(): string
    {
        return match($this->tipo_cita) {
            self::TIPO_DIGITAL => 'Digital',
            self::TIPO_PRESENCIAL => 'Presencial',
            self::TIPO_DOMICILIARIA => 'Domiciliaria',
            default => $this->tipo_cita
        };
    }

    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha_cita->format('d/m/Y H:i');
    }

    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            self::ESTADO_ASIGNADA => 'blue',
            self::ESTADO_CANCELADA => 'red',
            self::ESTADO_ASISTIDA => 'green',
            self::ESTADO_NO_ASISTIO => 'orange',
            default => 'gray'
        };
    }

    public function estaVencida(): bool
    {
        return $this->fecha_cita->isPast() && $this->estado === self::ESTADO_ASIGNADA;
    }

    public function puedeSerCancelada(): bool
    {
        return in_array($this->estado, [self::ESTADO_ASIGNADA]);
    }

    public function puedeSerMarcadaComoAsistida(): bool
    {
        return $this->estado === self::ESTADO_ASIGNADA && $this->fecha_cita->isPast();
    }

    public function scopeAsignadas($query)
    {
        return $query->where('estado', self::ESTADO_ASIGNADA);
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', self::ESTADO_ASIGNADA)
                     ->where('fecha_cita', '<', now());
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_cita', today());
    }

    public function scopeEstaSemana($query)
    {
        return $query->whereBetween('fecha_cita', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }
} 