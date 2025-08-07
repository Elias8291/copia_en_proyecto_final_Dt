<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'tipo',
        'titulo',
        'mensaje',
        'leida',
        'datos_adicionales',
        'accion_url',
        'fecha_lectura'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'datos_adicionales' => 'array',
        'fecha_lectura' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot del modelo para establecer ordenamiento por defecto
     */
    protected static function boot()
    {
        parent::boot();
        
        // Ordenar por defecto por las más recientes primero
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('created_at', 'desc');
        });
    }

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Scopes para consultas comunes
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopeLeidas($query)
    {
        return $query->where('leida', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeDelUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeRecientes($query, $dias = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($dias));
    }

    // Métodos de ayuda
    public function marcarComoLeida()
    {
        $this->update([
            'leida' => true,
            'fecha_lectura' => now()
        ]);
    }

    public function esLeida()
    {
        return $this->leida;
    }

    public function tieneAccion()
    {
        return !empty($this->accion_url);
    }

    // Método estático para crear notificaciones fácilmente
    public static function crear($usuarioId, $tipo, $titulo, $mensaje, $datosAdicionales = null, $accionUrl = null)
    {
        return static::create([
            'usuario_id' => $usuarioId,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'datos_adicionales' => $datosAdicionales,
            'accion_url' => $accionUrl,
            'leida' => false
        ]);
    }

    // Obtener el color de badge según el tipo
    public function getColorBadgeAttribute()
    {
        $colores = [
            'exito' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'advertencia' => 'bg-amber-100 text-amber-700 border-amber-200',
            'error' => 'bg-red-100 text-red-700 border-red-200',
            'Tramite' => 'bg-blue-100 text-blue-700 border-blue-200',
            'Cita' => 'bg-purple-100 text-purple-700 border-purple-200',
            'informativo' => 'bg-gray-100 text-gray-700 border-gray-200'
        ];

        return $colores[$this->tipo] ?? $colores['informativo'];
    }

    // Obtener el estado de lectura formateado
    public function getEstadoLecturaAttribute()
    {
        return $this->leida ? 'Leída' : 'No leída';
    }
}
