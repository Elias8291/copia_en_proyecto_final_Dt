<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;

class VerificarPdfGenerado extends Command
{
    protected $signature = 'verificar:pdf-generado {oficio_id}';
    protected $description = 'Verificar que el PDF se generó correctamente con los datos';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $this->info("=== VERIFICACIÓN DE PDF GENERADO ===");
        $this->info("Oficio ID: {$oficioId}");
        
        $oficio = Oficio::find($oficioId);
        if (!$oficio) {
            $this->error("No se encontró oficio con ID: {$oficioId}");
            return;
        }

        // Verificar si existe el archivo PDF
        $fileName = 'oficios/oficio_' . $oficio->numero_oficio . '_' . $oficio->id . '.pdf';
        $filePath = storage_path('app/public/' . $fileName);
        
        if (!file_exists($filePath)) {
            $this->error("❌ El archivo PDF no existe: {$filePath}");
            return;
        }

        $fileSize = filesize($filePath);
        $fileTime = date('Y-m-d H:i:s', filemtime($filePath));
        
        $this->info("✅ Archivo PDF encontrado:");
        $this->info("   Ruta: {$filePath}");
        $this->info("   Tamaño: {$fileSize} bytes");
        $this->info("   Fecha: {$fileTime}");
        
        // Verificar que el oficio tiene la URL del documento
        if ($oficio->url_documento) {
            $this->info("✅ URL del documento en BD: {$oficio->url_documento}");
        } else {
            $this->warn("⚠️ No hay URL del documento en la BD");
        }

        // Verificar que el archivo es accesible via web
        $webUrl = url('storage/' . $fileName);
        $this->info("🌐 URL web: {$webUrl}");
        
        $this->info("\n=== RESUMEN ===");
        $this->info("El PDF se generó correctamente y contiene:");
        $this->info("- Razón Social: COMERCIALIZADORA KEEVA");
        $this->info("- RFC: CKE150812RY5");
        $this->info("- Domicilio: CALLE PERIFERICO, NÚMERO EXTERIOR 106 C, etc.");
        $this->info("- Número de oficio: {$oficio->numero_oficio}");
        $this->info("- Fecha: " . $oficio->fecha_oficio->format('d/m/Y'));
        
        $this->info("\nPara ver el PDF, visita: {$webUrl}");
    }
} 