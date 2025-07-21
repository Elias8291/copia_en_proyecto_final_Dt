<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoArchivo extends Model
{
    use HasFactory;

    protected $table = 'catalogo_archivos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_persona',
        'tipo_archivo',
        'es_visible',
    ];

    protected $casts = [
        'es_visible' => 'boolean',
    ];

    // Scopes para filtrado
    public function scopeVisible($query)
    {
        return $query->where('es_visible', true);
    }

    public function scopeTipoPersona($query, $tipo)
    {
        if ($tipo) {
            return $query->where('tipo_persona', $tipo)
                         ->orWhere('tipo_persona', 'Ambas');
        }
        return $query;
    }

    public function scopeTipoArchivo($query, $tipo)
    {
        if ($tipo) {
            return $query->where('tipo_archivo', $tipo);
        }
        return $query;
    }

    public function scopeBuscar($query, $busqueda)
    {
        if ($busqueda) {
            return $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }
        return $query;
    }

    // Accessors
    public function getTipoPersonaLabelAttribute()
    {
        return match($this->tipo_persona) {
            'Física' => 'Persona Física',
            'Moral' => 'Persona Moral',
            'Ambas' => 'Ambos tipos',
            default => $this->tipo_persona
        };
    }

    public function getTipoArchivoLabelAttribute()
    {
        return match($this->tipo_archivo) {
            'png' => 'Imagen PNG',
            'pdf' => 'Documento PDF',
            'mp3' => 'Audio MP3',
            default => strtoupper($this->tipo_archivo)
        };
    }

    public function getEstadoLabelAttribute()
    {
        return $this->es_visible ? 'Visible' : 'Oculto';
    }
} 