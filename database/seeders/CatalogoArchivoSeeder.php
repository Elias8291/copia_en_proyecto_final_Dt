<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogoArchivoSeeder extends Seeder
{
    public function run(): void
    {
        $archivos = [
            // Archivos para ambas personas
            $this->crearArchivo('Constancia de situación fiscal', 'Documento emitido por la Secretaría de Hacienda y Crédito Público, actualizado, con fecha de expedición no mayor de tres meses anteriores a la fecha de solicitud.', 'Ambas', 'pdf'),
            $this->crearArchivo('Identificación oficial', 'Copia simple de la identificación oficial con fotografía vigente de la persona o del representante legal.', 'Ambas', 'pdf'),
            $this->crearArchivo('Currículum actualizado', 'Documento que contiene el giro, experiencia, relación de principales clientes, recursos materiales y humanos del proveedor.', 'Ambas', 'pdf'),
            $this->crearArchivo('Comprobante de domicilio fiscal', 'Comprobante de domicilio fiscal con fecha de expedición no mayor de tres meses anteriores a la fecha de solicitud.', 'Ambas', 'pdf'),
            $this->crearArchivo('Croquis y fotografías del domicilio', 'Documento que incluye el croquis de localización y fotografías del domicilio del proveedor.', 'Ambas', 'pdf'),
            $this->crearArchivo('Carta poder simple', 'Carta poder simple acompañada de la copia de la identificación oficial con fotografía del aceptante, cuando sea una persona distinta al solicitante o al representante legal.', 'Ambas', 'pdf'),
            $this->crearArchivo('Acuse de recibo de declaraciones', 'Copia simple del acuse de recibo emitido por el Servicio de Administración Tributaria correspondiente a la última declaración anual de impuestos y de las declaraciones provisionales.', 'Ambas', 'pdf'),
            $this->crearArchivo('Video del domicilio fiscal', 'Video que muestra el domicilio fiscal del proveedor, evidenciando su ubicación y características.', 'Ambas', 'mp4'),
            
            // Archivos solo para persona física
            $this->crearArchivo('Acta de nacimiento', 'Acta de nacimiento actualizada, con fecha de expedición no mayor de tres meses anteriores a la fecha de solicitud.', 'Física', 'pdf'),
            $this->crearArchivo('Clave Única de Registro de Población', 'Copia simple de la Clave Única de Registro de Población (CURP).', 'Física', 'pdf'),
            
            // Archivos solo para persona moral
            $this->crearArchivo('Acta constitutiva', 'Copia simple del acta constitutiva notariada de la sociedad, debidamente inscrita en el Registro Público de la Propiedad, incluyendo sus modificaciones si las hubiera.', 'Moral', 'pdf'),
            $this->crearArchivo('Poder general notariado', 'Copia simple del poder general notariado para actos de administración del representante o apoderado legal.', 'Moral', 'pdf'),
        ];

        foreach ($archivos as $archivo) {
            DB::table('catalogo_archivos')->updateOrInsert(
                ['nombre' => $archivo['nombre']],
                $archivo
            );
        }
    }

    private function crearArchivo(string $nombre, string $descripcion, string $tipoPersona, string $tipoArchivo): array
    {
        return [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'tipo_persona' => $tipoPersona,
            'tipo_archivo' => $tipoArchivo,
            'es_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}