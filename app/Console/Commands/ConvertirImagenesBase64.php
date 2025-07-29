<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertirImagenesBase64 extends Command
{
    protected $signature = 'convertir:imagenes-base64';
    protected $description = 'Convertir imágenes a base64 para el PDF';

    public function handle()
    {
        $this->info("=== CONVERSIÓN DE IMÁGENES A BASE64 ===");
        
        $logoEncabezado = public_path('images/logo_encabezado2022.jpg');
        $logoLateral = public_path('images/logo_lateral2022.jpg');
        
        if (!file_exists($logoEncabezado)) {
            $this->error("❌ No se encontró: {$logoEncabezado}");
            return;
        }
        
        if (!file_exists($logoLateral)) {
            $this->error("❌ No se encontró: {$logoLateral}");
            return;
        }
        
        $this->info("✅ Imágenes encontradas");
        
        // Convertir a base64
        $base64Encabezado = base64_encode(file_get_contents($logoEncabezado));
        $base64Lateral = base64_encode(file_get_contents($logoLateral));
        
        $this->info("✅ Imágenes convertidas a base64");
        $this->info("Tamaño logo encabezado: " . strlen($base64Encabezado) . " caracteres");
        $this->info("Tamaño logo lateral: " . strlen($base64Lateral) . " caracteres");
        
        $this->info("\n=== CÓDIGO PARA LA VISTA ===");
        $this->info("Logo Encabezado:");
        $this->line('src="data:image/jpeg;base64,' . $base64Encabezado . '"');
        
        $this->info("\nLogo Lateral:");
        $this->line('src="data:image/jpeg;base64,' . $base64Lateral . '"');
    }
} 