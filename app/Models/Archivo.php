<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $table = 'archivos';

    protected $fillable = [
        'nombre_original',
        'ruta_archivo',
        'idCatalogoArchivo',
        'observaciones',
        'fecha_cotejo',
        'cotejado_por',
        'aprobado',
        'tramite_id',
    ];

    protected $casts = [
        'fecha_cotejo' => 'datetime',
        'aprobado' => 'boolean',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function catalogoArchivo()
    {
        return $this->belongsTo(CatalogoArchivo::class, 'idCatalogoArchivo');
    }

    public function cotejadoPor()
    {
        return $this->belongsTo(User::class, 'cotejado_por');
    }

    /**
     * Generar URL para visualizar el documento
     */
    public function getUrlVisualizacionAttribute()
    {
        if ($this->tramite_id) {
            $filename = basename($this->ruta_archivo);
            return route('revision.verDocumento', [
                'tramite' => $this->tramite_id,
                'archivo' => $this->id,
                'filename' => $filename
            ]);
        }
        return null;
    }

    /**
     * Generar URL para descargar el documento
     */
    public function getUrlDescargaAttribute()
    {
        return $this->getUrlVisualizacionAttribute();
    }
} 