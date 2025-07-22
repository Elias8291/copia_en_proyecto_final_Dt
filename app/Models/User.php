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


    public function proveedor()
    {
        return $this->hasOne(Proveedor::class, 'usuario_id');
    }
}
