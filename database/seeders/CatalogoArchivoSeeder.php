<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogoArchivoSeeder extends Seeder
{
    public function run(): void
    {
        $archivos = [
            [
                'nombre' => 'Constancia de situación fiscal',
                'descripcion' => 'Documento emitido por la Secretaría de Hacienda y Crédito Público, actualizado, con fecha de expedición no mayor de tres meses anteriores a la fecha de solicitud.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Identificación oficial',
                'descripcion' => 'Copia simple de la identificación oficial con fotografía vigente de la persona o del representante legal.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Currículum actualizado',
                'descripcion' => 'Documento que contiene el giro, experiencia, relación de principales clientes, recursos materiales y humanos del proveedor.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Comprobante de domicilio fiscal',
                'descripcion' => 'Comprobante de domicilio fiscal con fecha de expedición no mayor de tres meses anteriores a la fecha de solicitud.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Croquis y fotografías del domicilio',
                'descripcion' => 'Documento que incluye el croquis de localización y fotografías del domicilio del proveedor.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Carta poder simple',
                'descripcion' => 'Carta poder simple acompañada de la copia de la identificación oficial con fotografía del aceptante, cuando sea una persona distinta al solicitante o al representante legal.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Acuse de recibo de declaraciones',
                'descripcion' => 'Copia simple del acuse de recibo emitido por el Servicio de Administración Tributaria correspondiente a la última declaración anual de impuestos y de las declaraciones provisionales.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Video del domicilio fiscal',
                'descripcion' => 'Video que muestra el domicilio fiscal del proveedor, evidenciando su ubicación y características.',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'mp3',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Acta de nacimiento',
                'descripcion' => 'Acta de nacimiento actualizada, con fecha de expedición no mayor de tres meses anteriores a la fecha de solicitud.',
                'tipo_persona' => 'Física',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Clave Única de Registro de Población',
                'descripcion' => 'Copia simple de la Clave Única de Registro de Población (CURP).',
                'tipo_persona' => 'Física',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Acta constitutiva',
                'descripcion' => 'Copia simple del acta constitutiva notariada de la sociedad, debidamente inscrita en el Registro Público de la Propiedad, incluyendo sus modificaciones si las hubiera.',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Poder general notariado',
                'descripcion' => 'Copia simple del poder general notariado para actos de administración del representante o apoderado legal.',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insertar o actualizar cada archivo del catálogo
        foreach ($archivos as $archivo) {
            DB::table('catalogo_archivos')->updateOrInsert(
                ['nombre' => $archivo['nombre']], // Condición para buscar
                $archivo // Datos a insertar o actualizar
            );
        }
    }
}