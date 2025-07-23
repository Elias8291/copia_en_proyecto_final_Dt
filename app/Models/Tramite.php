<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'proveedor_id',
        'tipo_tramite',
        'estado',
        'fecha_inicio',
        'paso_actual',
        'fecha_finalizacion',
        'observaciones',
        'revisado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_finalizacion' => 'datetime',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function procesadoPor()
    {
        return $this->belongsTo(User::class, 'procesado_por');
    }

    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function datosProveedor()
    {
        return $this->hasOne(DatosProveedor::class);
    }

    // Nuevas relaciones para el formulario completo
    public function datosGenerales()
    {
        return $this->hasOne(DatosGenerales::class);
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'id_tramite');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class);
    }

    public function accionistas()
    {
        return $this->hasMany(Accionista::class);
    }

    public function actividades()
    {
        return $this->belongsToMany(ActividadEconomica::class, 'actividades', 'tramite_id', 'actividad_id');
    }

    public function datosConstitutivos()
    {
        return $this->hasOne(DatosConstitutivos::class);
    }

    public function apoderadoLegal()
    {
        return $this->hasOne(ApoderadoLegal::class);
    }

    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }

    /**
     * Obtiene el tiempo transcurrido desde la creación del trámite
     */
    public function getTiempoTranscurridoAttribute(): string
    {
        $diferencia = $this->created_at->diff(now());

        if ($diferencia->days > 30) {
            $tiempo = $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
            if ($diferencia->d > 0) {
                $tiempo .= ' y ' . $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
            }
            return $tiempo;
        }

        if ($diferencia->days > 0) {
            $tiempo = $diferencia->days . ' día' . ($diferencia->days > 1 ? 's' : '');
            if ($diferencia->h > 0) {
                $tiempo .= ' y ' . $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
            }
            return $tiempo;
        }

        if ($diferencia->h > 0) {
            $tiempo = $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
            if ($diferencia->i > 0) {
                $tiempo .= ' y ' . $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
            }
            return $tiempo;
        }

        return $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
    }
}
