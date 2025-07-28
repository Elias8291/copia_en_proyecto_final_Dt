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

    // Accessors
    public function getTipoPersonaLabelAttribute()
    {
        return match ($this->tipo_persona) {
            'Física' => 'Persona Física',
            'Moral' => 'Persona Moral',
            'Ambas' => 'Ambos tipos',
            default => $this->tipo_persona
        };
    }

    public function getTipoArchivoLabelAttribute()
    {
        return match ($this->tipo_archivo) {
            'png' => 'Imagen PNG',
            'pdf' => 'Documento PDF',
            'mp3' => 'Audio MP3',
            'mp4' => 'Video MP4',
            default => strtoupper($this->tipo_archivo)
        };
    }

    public function getEstadoLabelAttribute()
    {
        return $this->es_visible ? 'Visible' : 'Oculto';
    }

    public function getMimeTypeAttribute()
    {
        return match ($this->tipo_archivo) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'pdf' => 'application/pdf',
            'mp3' => 'audio/mpeg',
            'mp4' => 'video/mp4',
            default => 'application/octet-stream'
        };
    }

    public function getAcceptAttribute()
    {
        return match ($this->tipo_archivo) {
            'png' => '.png,.jpg,.jpeg',
            'jpg', 'jpeg' => '.jpg,.jpeg,.png',
            'pdf' => '.pdf',
            'mp3' => '.mp3',
            'mp4' => '.mp4,.avi,.mov,.wmv',
            default => '.' . $this->tipo_archivo
        };
    }
}
