<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Oficio extends Model
{
    protected $table = 'oficios';

    protected $fillable = [
        'numero_oficio',
        'fecha_oficio',
        'url',
        'contenido',
        'tramite_id',
        'proveedor_id',
        'estado'
    ];

    protected $casts = [
        'fecha_oficio' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación con el trámite
     */
    public function tramite(): BelongsTo
    {
        return $this->belongsTo(Tramite::class);
    }

    /**
     * Relación con el proveedor
     */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Generar número de oficio automáticamente
     */
    public static function generarNumeroOficio(): string
    {
        $ultimoOficio = self::orderBy('id', 'desc')->first();
        $anio = Carbon::now()->year;
        
        if (!$ultimoOficio) {
            return "OF-{$anio}-001";
        }
        
        $ultimoNumero = (int) substr($ultimoOficio->numero_oficio, -3);
        $nuevoNumero = str_pad($ultimoNumero + 1, 3, '0', STR_PAD_LEFT);
        
        return "OF-{$anio}-{$nuevoNumero}";
    }

    /**
     * Crear oficio con datos del proveedor
     */
    public static function crearOficioConProveedor(Tramite $tramite, string $url = null, string $contenido = null): self
    {
        $numeroOficio = self::generarNumeroOficio();
        
        return self::create([
            'numero_oficio' => $numeroOficio,
            'fecha_oficio' => Carbon::now(),
            'url' => $url,
            'contenido' => $contenido,
            'tramite_id' => $tramite->id,
            'proveedor_id' => $tramite->proveedor_id,
            'estado' => 'Generado'
        ]);
    }

    /**
     * Obtener URL completa del oficio
     */
    public function getUrlCompletaAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }
        
        // Si no hay URL, generar una URL para descargar el oficio
        return route('oficios.descargar', $this->id);
    }

    /**
     * Verificar si el oficio tiene URL
     */
    public function tieneUrl(): bool
    {
        return !empty($this->url);
    }

    /**
     * Verificar si el oficio tiene contenido
     */
    public function tieneContenido(): bool
    {
        return !empty($this->contenido);
    }

    /**
     * Obtener estado formateado
     */
    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            'Generado' => 'Generado',
            'Enviado' => 'Enviado',
            'Entregado' => 'Entregado',
            'Cancelado' => 'Cancelado',
            default => $this->estado
        };
    }

    /**
     * Obtener color del estado
     */
    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            'Generado' => 'bg-blue-100 text-blue-800',
            'Enviado' => 'bg-yellow-100 text-yellow-800',
            'Entregado' => 'bg-green-100 text-green-800',
            'Cancelado' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
} 