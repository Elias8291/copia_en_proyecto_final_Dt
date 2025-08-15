<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'nombre',
        'correo',
        'rfc',
        'password',
        'confirmacion',
        'confirmation_token',
        'verification_token',
        'ultimo_acceso',
    ];

    // Exponer alias compatibles con vistas/consultas genericas
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nombre'] ?? null;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->attributes['correo'] ?? null;
    }


    public function proveedor()
    {
        return $this->hasOne(Proveedor::class, 'usuario_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(\App\Models\Notificacion::class, 'usuario_id');
    }

    public function tramitesAsignados()
    {
        return $this->hasMany(Tramite::class, 'revisor_digital_id');
    }

    public function citasAsignadas()
    {
        return $this->hasMany(Cita::class, 'asignado_a');
    }

    public function revisiones()
    {
        return $this->hasMany(RevisionTramite::class, 'revisor_id');
    }

    /**
     * Get the email address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }
}
