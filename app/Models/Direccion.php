<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Direccion extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'direcciones';

    protected $fillable = [
        'proveedor_id',
        'calle',
        'entre_calles',
        'numero_exterior',
        'numero_interior',
        'codigo_postal',
        'colonia_asentamiento',
        'municipio',
        'estado_id',
        'coordenadas_id',
        'es_principal',
        'activo',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'activo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Dirección {$eventName}")
            ->useLogName('direccion');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function coordenadas()
    {
        return $this->belongsTo(Coordenada::class);
    }
}
