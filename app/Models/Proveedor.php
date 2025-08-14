<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    
    protected $fillable = [
        'usuario_id',
        'pv_numero',
        'token_publico',
        'rfc',
        'razon_social',
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

    /**
     * Sincronizar datos generales en el proveedor
     */
    public function sincronizarDatosGenerales($datosGenerales)
    {
        \Log::info('Sincronizando datos generales en proveedor', [
            'proveedor_id' => $this->id,
            'razon_social_anterior' => $this->razon_social,
            'razon_social_nueva' => $datosGenerales->razon_social
        ]);

        $this->update([
            'razon_social' => $datosGenerales->razon_social,
        ]);

        // Sincronizar también con el usuario asociado
        if ($this->usuario) {
            \Log::info('Sincronizando nombre del usuario con razón social', [
                'usuario_id' => $this->usuario->id,
                'nombre_anterior' => $this->usuario->nombre,
                'nombre_nuevo' => $datosGenerales->razon_social
            ]);

            $this->usuario->update([
                'nombre' => $datosGenerales->razon_social
            ]);
        }

        return $this;
    }

    /**
     * Generar token público seguro para URLs
     */
    public function generarTokenPublico(): string
    {
        do {
            $token = Str::random(32) . hash('sha256', $this->id . $this->rfc . now()->timestamp);
            $token = substr($token, 0, 64); // Limitar a 64 caracteres
        } while (self::where('token_publico', $token)->exists());

        $this->update(['token_publico' => $token]);
        
        \Log::info('Token público generado', [
            'proveedor_id' => $this->id,
            'token_length' => strlen($token)
        ]);

        return $token;
    }

    /**
     * Obtener o generar token público
     */
    public function obtenerTokenPublico(): string
    {
        if (empty($this->token_publico)) {
            return $this->generarTokenPublico();
        }
        
        return $this->token_publico;
    }

    /**
     * Buscar proveedor por token público
     */
    public static function buscarPorToken(string $token): ?self
    {
        return self::where('token_publico', $token)->first();
    }
} 