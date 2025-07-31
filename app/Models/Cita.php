<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'tramite_id',
        'id_tramite',
        'proveedor_id',
        'user_id',
        'fecha_cita',
        'tipo_cita',
        'estado',
        'contador_reagendamientos',
        'max_reagendamientos',
        'atendido_por',
        'observaciones',
        'motivo'
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function atendidoPor()
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Obtiene la fecha formateada de la cita
     */
    public function getFechaFormateadaAttribute()
    {
        if (!$this->fecha_cita) {
            return 'Por confirmar';
        }
        
        return $this->fecha_cita->format('d/m/Y H:i');
    }

    /**
     * Obtiene el estado de la cita en español
     */
    public function getEstadoLabelAttribute()
    {
        return match ($this->estado) {
            'Programada' => 'Programada',
            'Confirmada' => 'Confirmada',
            'Cancelada' => 'Cancelada',
            'Reagendada' => 'Reagendada',
            'Completada' => 'Completada',
            default => $this->estado
        };
    }

    /**
     * Obtiene el tipo de cita en español
     */
    public function getTipoCitaLabelAttribute()
    {
        return match ($this->tipo_cita) {
            'Revision' => 'Revisión',
            'Cotejo' => 'Cotejo',
            'Entrega' => 'Entrega',
            'Consulta' => 'Consulta',
            'Otro' => 'Otro',
            'Reunion' => 'Reunión',
            'Administrativa' => 'Administrativa',
            default => $this->tipo_cita
        };
    }
} 