<?php

namespace App\Helpers;

class ArchivosHelper
{
    /**
     * Obtener tipos MIME permitidos según el tipo de archivo
     */
    public static function obtenerTiposMimePorTipoArchivo($tipoArchivo)
    {
        return match($tipoArchivo) {
            'pdf' => 'pdf',
            'png' => 'png,jpg,jpeg,gif,webp',
            'mp3' => 'mp3,wav,ogg',
            'mp4' => 'mp4,avi,mov,wmv,flv,webm',
            default => 'pdf,png,jpg,jpeg,gif,webp,mp3,wav,ogg,mp4,avi,mov,wmv,flv,webm'
        };
    }
    
    /**
     * Obtener tipos permitidos legibles
     */
    public static function obtenerTiposPermitidosLegibles($tipoArchivo)
    {
        return match($tipoArchivo) {
            'pdf' => 'PDF',
            'png' => 'PNG, JPG, JPEG, GIF, WEBP',
            'mp3' => 'MP3, WAV, OGG',
            'mp4' => 'MP4, AVI, MOV, WMV, FLV, WEBM',
            default => 'PDF, PNG, JPG, JPEG, GIF, WEBP, MP3, WAV, OGG, MP4, AVI, MOV, WMV, FLV, WEBM'
        };
    }
} 