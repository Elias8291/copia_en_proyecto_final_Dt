<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    
    protected $fillable = [
        'usuario_id',
        'pv_numero',
        'rfc',
        'tipo_persona',
        'estado_padron',
        'fecha_alta_padron',
        'fecha_vencimiento_padron'
    ];

    protected $casts = [
        'fecha_alta_padron' => 'date',
        'fecha_vencimiento_padron' => 'date'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tramites(): HasMany
    {
        return $this->hasMany(Tramite::class);
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
} 