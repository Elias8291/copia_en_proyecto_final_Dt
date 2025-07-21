<?php

namespace Database\Seeders;

use App\Models\CatalogoArchivo;
use Illuminate\Database\Seeder;

class CatalogoArchivoSeeder extends Seeder
{
    public function run(): void
    {
        $archivos = [
            // Documentos PDF - Persona Física
            [
                'nombre' => 'Identificación Oficial',
                'descripcion' => 'Credencial de elector, pasaporte o cédula profesional vigente',
                'tipo_persona' => 'Física',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'CURP',
                'descripcion' => 'Clave Única de Registro de Población actualizada',
                'tipo_persona' => 'Física',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Comprobante de Domicilio',
                'descripcion' => 'Recibo de servicios no mayor a 3 meses de antigüedad',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            
            // Documentos PDF - Persona Moral
            [
                'nombre' => 'Acta Constitutiva',
                'descripcion' => 'Escritura pública de constitución de la sociedad',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Poder Notarial',
                'descripcion' => 'Poder del representante legal vigente',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'RFC de la Empresa',
                'descripcion' => 'Registro Federal de Contribuyentes actualizado',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Estados Financieros',
                'descripcion' => 'Balance general y estado de resultados del último ejercicio',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            
            // Documentos comunes
            [
                'nombre' => 'Constancia de Situación Fiscal',
                'descripcion' => 'Documento emitido por el SAT con datos actuales',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Opinión de Cumplimiento',
                'descripcion' => 'Opinión positiva del SAT sobre obligaciones fiscales',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            
            // Imágenes PNG
            [
                'nombre' => 'Logotipo de la Empresa',
                'descripcion' => 'Logo oficial en alta resolución para documentos',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'png',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Foto del Establecimiento',
                'descripcion' => 'Fotografía del local comercial o planta industrial',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'png',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Fotografía del Representante',
                'descripcion' => 'Foto tamaño credencial del representante legal',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'png',
                'es_visible' => true,
            ],
            
            // Archivos de Audio MP3
            [
                'nombre' => 'Grabación de Entrevista',
                'descripcion' => 'Audio de la entrevista realizada al solicitante',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'mp3',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Declaración Jurada',
                'descripcion' => 'Audio con la declaración bajo protesta de decir verdad',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'mp3',
                'es_visible' => false,
            ],
            
            // Documentos adicionales
            [
                'nombre' => 'Carta de Antecedentes No Penales',
                'descripcion' => 'Constancia de no tener antecedentes penales',
                'tipo_persona' => 'Física',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Licencia de Funcionamiento',
                'descripcion' => 'Permiso municipal para operar el negocio',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Certificado de Calidad',
                'descripcion' => 'Certificaciones ISO o similares (si aplica)',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => false,
            ],
            [
                'nombre' => 'Manual de Procedimientos',
                'descripcion' => 'Documento interno de procesos empresariales',
                'tipo_persona' => 'Moral',
                'tipo_archivo' => 'pdf',
                'es_visible' => false,
            ],
            [
                'nombre' => 'Lista de Precios',
                'descripcion' => 'Catálogo actualizado de productos o servicios',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
            [
                'nombre' => 'Contrato de Arrendamiento',
                'descripcion' => 'Documento que acredita la posesión del inmueble',
                'tipo_persona' => 'Ambas',
                'tipo_archivo' => 'pdf',
                'es_visible' => true,
            ],
        ];

        foreach ($archivos as $archivo) {
            CatalogoArchivo::create($archivo);
        }

        $this->command->info('Catálogo de archivos creado exitosamente con ' . count($archivos) . ' registros.');
    }
} 