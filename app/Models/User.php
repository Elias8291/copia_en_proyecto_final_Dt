<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Solicitante;
use App\Models\Tramite;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
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
        'created_at',
        'updated_at',
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

    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->correo)->send(new ResetPassword($token, $this->correo));
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    public function getEmailAttribute()
    {
        return $this->correo;
    }

    public function getNameAttribute()
    {
        return $this->nombre;
    }

    public function getEmailForVerification()
    {
        return $this->correo;
    }

    /**
     * Relación: proveedor del usuario
     */
    public function proveedor()
    {
        return $this->hasOne(Proveedor::class, 'user_id');
    }
}