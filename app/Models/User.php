<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'nombre',
        'correo',
        'rfc',
        'password',
        'estado',
        'verification_token',
        'fecha_verificacion_correo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_verificacion_correo' => 'datetime',
        'ultimo_acceso' => 'datetime',
        'password' => 'hashed',
    ];

    public function getEmailForVerification()
    {
        return $this->correo;
    }

    public function proveedor()
    {
        return $this->hasOne(Proveedor::class, 'usuario_id');
    }
}
