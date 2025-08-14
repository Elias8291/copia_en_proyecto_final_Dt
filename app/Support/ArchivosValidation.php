<?php

namespace App\Support;

use Illuminate\Support\Str;

class ArchivosValidation
{
    public static function maximoKbPorTipo(string $tipo): int
    {
        $t = strtolower($tipo);
        return match ($t) {
            'pdf' => 5120,
            'mp4' => 10240,
            'mp3' => 10240,
            'png', 'jpg', 'jpeg', 'gif', 'webp' => 5120,
            default => 5120,
        };
    }

    public static function maximoMbPorTipo(string $tipo): int
    {
        return (int) round(self::maximoKbPorTipo($tipo) / 1024);
    }

    public static function extensionesPermitidasPorTipoCatalogo(string $tipoCatalogo): string
    {
        return match (strtolower($tipoCatalogo)) {
            'pdf' => 'pdf',
            'png' => 'png,jpg,jpeg,gif,webp',
            'mp3' => 'mp3,wav,ogg',
            'mp4' => 'mp4,avi,mov,wmv,flv,webm',
            default => 'pdf,png,jpg,jpeg,gif,webp,mp3,wav,ogg,mp4,avi,mov,wmv,flv,webm',
        };
    }

    public static function etiquetasPorTipoCatalogo(string $tipoCatalogo): string
    {
        return match (strtolower($tipoCatalogo)) {
            'pdf' => 'PDF',
            'png' => 'PNG, JPG, JPEG, GIF, WEBP',
            'mp3' => 'MP3, WAV, OGG',
            'mp4' => 'MP4, AVI, MOV, WMV, FLV, WEBM',
            default => 'PDF, PNG, JPG, JPEG, GIF, WEBP, MP3, WAV, OGG, MP4, AVI, MOV, WMV, FLV, WEBM',
        };
    }

    public static function extensionesPermitidasPorExtension(string $extension): string
    {
        return match (strtolower($extension)) {
            'pdf' => 'pdf',
            'mp4' => 'mp4',
            'mp3' => 'mp3',
            'png', 'jpg', 'jpeg', 'gif', 'webp' => 'png,jpg,jpeg,gif,webp',
            default => 'pdf,png,jpg,jpeg,gif,webp,mp3,mp4',
        };
    }

    public static function etiquetasPorExtension(string $extension): string
    {
        return match (strtolower($extension)) {
            'pdf' => 'PDF',
            'mp4' => 'MP4',
            'mp3' => 'MP3',
            'png', 'jpg', 'jpeg', 'gif', 'webp' => 'PNG, JPG, JPEG, GIF, WEBP',
            default => 'PDF, PNG, JPG, JPEG, GIF, WEBP, MP3, MP4',
        };
    }

    public static function construirReglasParaCatalogo($archivosRequeridos, bool $esCorreccion = false, string $prefijo = 'documentos.'): array
    {
        $rules = [];
        foreach ($archivosRequeridos as $archivo) {
            $campo = $prefijo . Str::slug($archivo->nombre);
            $tipo = is_string($archivo->tipo_archivo) ? strtolower($archivo->tipo_archivo) : '';
            $extensionesPermitidas = self::extensionesPermitidasPorTipoCatalogo($tipo);
            $maxKb = self::maximoKbPorTipo($tipo);
            $requerido = $esCorreccion ? 'nullable' : 'required';
            $rules[$campo] = $requerido . '|file|mimes:' . $extensionesPermitidas . '|max:' . $maxKb;
        }
        return $rules;
    }

    public static function construirMensajesParaCatalogo($archivosRequeridos, string $prefijo = 'documentos.'): array
    {
        $messages = [];
        foreach ($archivosRequeridos as $archivo) {
            $campo = $prefijo . Str::slug($archivo->nombre);
            $tipo = is_string($archivo->tipo_archivo) ? strtolower($archivo->tipo_archivo) : '';
            $etiquetas = self::etiquetasPorTipoCatalogo($tipo);
            $maxMb = self::maximoMbPorTipo($tipo);
            $messages[$campo . '.required'] = "El archivo '{$archivo->nombre}' es obligatorio.";
            $messages[$campo . '.file'] = "El archivo '{$archivo->nombre}' debe ser un archivo válido.";
            $messages[$campo . '.mimes'] = "El archivo '{$archivo->nombre}' debe ser de tipo: {$etiquetas}.";
            $messages[$campo . '.max'] = "El archivo '{$archivo->nombre}' no puede ser mayor a {$maxMb}MB.";
        }
        return $messages;
    }
}


