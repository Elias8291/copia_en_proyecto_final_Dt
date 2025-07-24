<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\LogOptions;
// use Spatie\Activitylog\Traits\LogsActivity;

class Direccion extends Model
{
    use HasFactory; // Removed SoftDeletes and LogsActivity traits temporarily

    protected $table = 'direcciones';

    protected $fillable = [
        'id_tramite',
        'calle',
        'entre_calles',
        'numero_exterior',
        'numero_interior',
        'codigo_postal',
        'colonia_asentamiento',
        'municipio',
        'id_estado',
        'coordenadas_id',
        // Removed non-existent columns: 'es_principal', 'activo'
    ];

    protected $casts = [
        // Removed casts for non-existent columns
        // 'es_principal' => 'boolean',
        // 'activo' => 'boolean',
    ];

    // Commented out activity log method temporarily
    /*
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Dirección {$eventName}")
            ->useLogName('direccion');
    }
    */

    public function tramite()
    {
        return $this->belongsTo(Tramite::class, 'id_tramite');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }

    // Commented out temporarily - Coordenada model doesn't exist
    /*
    public function coordenadas()
    {
        return $this->belongsTo(Coordenada::class);
    }
    */
}
