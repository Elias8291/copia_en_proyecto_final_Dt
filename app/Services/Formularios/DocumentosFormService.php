<?php

declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;
use App\Models\Archivo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class DocumentosFormService
{
    /**
     * Procesa y guarda los documentos adjuntos
     */
    public function procesar(Tramite $tramite, array $archivos): void
    {
        if (!empty($archivos)) {
            $this->guardarDocumentos($tramite, $archivos);
        }
    }

    /**
     * Guarda los documentos adjuntos
     */
    private function guardarDocumentos(Tramite $tramite, array $archivos): void
    {
        foreach ($archivos as $campo => $archivo) {
            if (is_array($archivo)) {
                foreach ($archivo as $index => $archivoIndividual) {
                    $this->procesarArchivo($tramite, $campo, $archivoIndividual, $index);
                }
            } else {
                $this->procesarArchivo($tramite, $campo, $archivo);
            }
        }
    }

    /**
     * Procesa un archivo individual
     */
    private function procesarArchivo(Tramite $tramite, string $campo, $archivo, ?int $index = null): void
    {
        if (!$this->esArchivoValido($archivo)) {
            return;
        }

        try {
            $catalogoId = $this->extraerCatalogoId($campo);
            $rutaArchivo = $this->almacenarArchivo($tramite, $archivo, $catalogoId, $index);
            
            $this->crearRegistroArchivo($tramite, $archivo, $rutaArchivo, $catalogoId);
            
        } catch (\Exception $e) {
            Log::warning('Error al procesar archivo', [
                'tramite_id' => $tramite->id,
                'campo' => $campo,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Verifica si el archivo es válido
     */
    private function esArchivoValido($archivo): bool
    {
        return $archivo instanceof UploadedFile && $archivo->isValid();
    }

    /**
     * Extrae el ID del catálogo del nombre del campo
     */
    private function extraerCatalogoId(string $campo): ?int
    {
        if (preg_match('/documentos\[(\d+)\]/', $campo, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    /**
     * Almacena el archivo en el storage
     */
    private function almacenarArchivo(Tramite $tramite, UploadedFile $archivo, ?int $catalogoId, ?int $index): string
    {
        $carpeta = "documentos/{$tramite->id}";
        
        if ($catalogoId && $index !== null) {
            $carpeta .= "/{$catalogoId}_{$index}";
        }
        
        return $archivo->store($carpeta, 'public');
    }

    /**
     * Crea el registro del archivo en la base de datos
     */
    private function crearRegistroArchivo(Tramite $tramite, UploadedFile $archivo, string $ruta, ?int $catalogoId): void
    {
        Archivo::create([
            'tramite_id' => $tramite->id,
            'idCatalogoArchivo' => $catalogoId,
            'nombre_original' => $archivo->getClientOriginalName(),
            'ruta_archivo' => $ruta,
            'aprobado' => false,
        ]);
    }

    /**
     * Valida los documentos adjuntos
     */
    public function validar(array $archivos): array
    {
        $errores = [];

        foreach ($archivos as $campo => $archivo) {
            if (is_array($archivo)) {
                foreach ($archivo as $index => $archivoIndividual) {
                    $errores = array_merge($errores, $this->validarArchivoIndividual($archivoIndividual, $campo, $index));
                }
            } else {
                $errores = array_merge($errores, $this->validarArchivoIndividual($archivo, $campo));
            }
        }

        return $errores;
    }

    /**
     * Valida un archivo individual
     */
    private function validarArchivoIndividual($archivo, string $campo, ?int $index = null): array
    {
        $errores = [];
        $etiqueta = $index !== null ? "{$campo}[{$index}]" : $campo;

        if (!($archivo instanceof UploadedFile)) {
            return $errores;
        }

        if (!$archivo->isValid()) {
            $errores[] = "El archivo {$etiqueta} no es válido";
            return $errores;
        }

        // Validar tamaño (10MB máximo)
        if ($archivo->getSize() > 10485760) {
            $errores[] = "El archivo {$etiqueta} excede el tamaño máximo de 10MB";
        }

        // Validar extensión
        $extensionesPermitidas = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $extension = strtolower($archivo->getClientOriginalExtension());
        
        if (!in_array($extension, $extensionesPermitidas)) {
            $errores[] = "El archivo {$etiqueta} debe ser: " . implode(', ', $extensionesPermitidas);
        }

        return $errores;
    }

    /**
     * Obtener documentos de un trámite organizados por tipo
     */
    public function obtenerDocumentos(Tramite $tramite): array
    {
        return $tramite->archivos->map(function($archivo) {
            return [
                'id' => $archivo->id,
                'nombre_original' => $archivo->nombre_original,
                'nombre_archivo' => $archivo->nombre_archivo,
                'tipo_documento' => $archivo->catalogoArchivo?->nombre ?? 'Documento',
                'extension' => $archivo->extension,
                'tamaño' => $archivo->tamaño,
                'fecha_subida' => $archivo->created_at->format('d/m/Y H:i'),
                'url_descarga' => '#',
                'descripcion' => $archivo->catalogoArchivo?->descripcion ?? '',
            ];
        })->groupBy('tipo_documento')->toArray();
    }

    /**
     * Obtener resumen de documentos
     */
    public function obtenerResumenDocumentos(Tramite $tramite): array
    {
        $archivos = $tramite->archivos;
        $totalTamaño = $archivos->sum('tamaño');
        
        return [
            'total_archivos' => $archivos->count(),
            'tamaño_total' => $this->formatearTamaño($totalTamaño),
            'tipos_documento' => $archivos->groupBy('catalogoArchivo.nombre')->keys()->toArray(),
            'ultimo_archivo' => $archivos->sortByDesc('created_at')->first()?->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Formatear tamaño de archivo
     */
    private function formatearTamaño(int $bytes): string
    {
        $unidades = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($unidades) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $unidades[$pow];
    }

    /**
     * Verificar si tiene documentos requeridos
     */
    public function tieneDocumentosRequeridos(Tramite $tramite): bool
    {
        // Aquí puedes definir qué documentos son requeridos según el tipo de trámite
        $documentosRequeridos = $this->obtenerDocumentosRequeridos($tramite->tipo_tramite);
        $documentosSubidos = $tramite->archivos->pluck('catalogoArchivo.nombre')->toArray();
        
        foreach ($documentosRequeridos as $requerido) {
            if (!in_array($requerido, $documentosSubidos)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Obtener documentos requeridos por tipo de trámite
     */
    private function obtenerDocumentosRequeridos(string $tipoTramite): array
    {
        return match(strtolower($tipoTramite)) {
            'inscripcion' => [
                'Constancia de Situación Fiscal',
                'Acta Constitutiva',
                'Identificación del Representante Legal',
            ],
            'renovacion' => [
                'Constancia de Situación Fiscal',
                'Comprobante de Domicilio',
            ],
            'actualizacion' => [
                'Constancia de Situación Fiscal',
            ],
            default => [],
        };
    }
}