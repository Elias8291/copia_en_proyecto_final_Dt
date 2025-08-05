<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tramite extends Model
{
    protected $table = 'tramites';
    
    protected $fillable = [
        'proveedor_id',
        'tipo_tramite',
        'status',
        'fecha_inicio',
        'fecha_finalizacion',
        'fecha_cancelacion',
        'observaciones',
        'correcciones_count',
        'paso_actual'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_finalizacion' => 'datetime',
        'fecha_cancelacion' => 'datetime'
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function datosGenerales(): HasMany
    {
        return $this->hasMany(DatoGeneral::class);
    }

    public function apoderadosLegales(): HasMany
    {
        return $this->hasMany(ApoderadoLegal::class);
    }

    public function datosConstitutivos(): HasMany
    {
        return $this->hasMany(DatoConstitutivo::class);
    }

    public function accionistas(): HasMany
    {
        return $this->hasMany(Accionista::class);
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(ActividadProveedor::class);
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class);
    }

    public function seccionesRevision(): HasMany
    {
        return $this->hasMany(SeccionRevision::class);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function revisiones(): HasMany
    {
        return $this->hasMany(RevisionTramite::class);
    }

    public function oficios(): HasMany
    {
        return $this->hasMany(Oficio::class);
    }

 
    public function getDatosGeneralesRecientes()
    {
        return $this->datosGenerales()->orderBy('created_at', 'desc')->first();
    }

   
    public function getRazonSocial()
    {
        $datosGenerales = $this->getDatosGeneralesRecientes();
        return $datosGenerales ? $datosGenerales->razon_social : null;
    }
} 