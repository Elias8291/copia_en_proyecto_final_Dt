<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Accionista extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'proveedor_id',
        'nombre_completo',
        'rfc',
        'porcentaje_participacion',
        'activo'
    ];

    protected $casts = [
        'porcentaje_participacion' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Accionista {$eventName}")
            ->useLogName('accionista');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}