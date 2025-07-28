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

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getTamañoFormateadoAttribute()
    {
        $ruta = storage_path('app/public/' . $this->ruta_archivo);
        
        if (file_exists($ruta)) {
            $bytes = filesize($ruta);
            return $this->formatearTamaño($bytes);
        }
        
        return 'N/A';
    }

    /**
     * Obtener la fecha de carga formateada
     */
    public function getFechaCargaAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : 'N/A';
    }

    /**
     * Formatear tamaño de bytes a formato legible
     */
    private function formatearTamaño($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
} 