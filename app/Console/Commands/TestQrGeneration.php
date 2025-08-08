<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class TestQrGeneration extends Command
{
    protected $signature = 'test:qr-generation {tramite_id=13}';
    protected $description = 'Probar la generación del QR independientemente';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Probando generación de QR para el trámite ID: {$tramiteId}");
        
        try {
            // Buscar el trámite
            $tramite = Tramite::with(['proveedor'])->find($tramiteId);
            
            if (!$tramite) {
                $this->error("❌ No se encontró el trámite con ID: {$tramiteId}");
                return 1;
            }
            
            $this->info("✅ Trámite encontrado: {$tramite->proveedor->razon_social}");
            
            // Crear instancia del servicio
            $oficioService = new OficioService();
            
            // Generar QR usando reflexión para acceder al método privado
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarQrCode');
            $method->setAccessible(true);
            
            $this->info("🔄 Generando QR...");
            $qrCode = $method->invoke($oficioService, $tramite);
            
            $this->info("📱 QR Code generado:");
            $this->line("   - Longitud: " . strlen($qrCode) . " caracteres");
            $this->line("   - Contiene HTML: " . (strpos($qrCode, '<') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene img tag: " . (strpos($qrCode, '<img') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene PNG: " . (strpos($qrCode, '.png') !== false ? 'Sí' : 'No'));
            
            // Mostrar el contenido del QR
            $this->info("📄 Contenido del QR:");
            $this->line($qrCode);
            
            // Verificar si el archivo QR existe
            $qrDirectory = storage_path('app/public/qr-codes');
            if (file_exists($qrDirectory)) {
                $this->info("✅ Directorio QR existe: {$qrDirectory}");
                $files = scandir($qrDirectory);
                $this->line("   - Archivos en el directorio: " . count($files) - 2);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $this->line("     - {$file}");
                    }
                }
            } else {
                $this->warn("⚠️ Directorio QR no existe: {$qrDirectory}");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar QR: " . $e->getMessage());
            Log::error('Error en TestQrGeneration', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class TestQrGeneration extends Command
{
    protected $signature = 'test:qr-generation {tramite_id=13}';
    protected $description = 'Probar la generación del QR independientemente';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Probando generación de QR para el trámite ID: {$tramiteId}");
        
        try {
            // Buscar el trámite
            $tramite = Tramite::with(['proveedor'])->find($tramiteId);
            
            if (!$tramite) {
                $this->error("❌ No se encontró el trámite con ID: {$tramiteId}");
                return 1;
            }
            
            $this->info("✅ Trámite encontrado: {$tramite->proveedor->razon_social}");
            
            // Crear instancia del servicio
            $oficioService = new OficioService();
            
            // Generar QR usando reflexión para acceder al método privado
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarQrCode');
            $method->setAccessible(true);
            
            $this->info("🔄 Generando QR...");
            $qrCode = $method->invoke($oficioService, $tramite);
            
            $this->info("📱 QR Code generado:");
            $this->line("   - Longitud: " . strlen($qrCode) . " caracteres");
            $this->line("   - Contiene HTML: " . (strpos($qrCode, '<') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene img tag: " . (strpos($qrCode, '<img') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene PNG: " . (strpos($qrCode, '.png') !== false ? 'Sí' : 'No'));
            
            // Mostrar el contenido del QR
            $this->info("📄 Contenido del QR:");
            $this->line($qrCode);
            
            // Verificar si el archivo QR existe
            $qrDirectory = storage_path('app/public/qr-codes');
            if (file_exists($qrDirectory)) {
                $this->info("✅ Directorio QR existe: {$qrDirectory}");
                $files = scandir($qrDirectory);
                $this->line("   - Archivos en el directorio: " . count($files) - 2);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $this->line("     - {$file}");
                    }
                }
            } else {
                $this->warn("⚠️ Directorio QR no existe: {$qrDirectory}");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar QR: " . $e->getMessage());
            Log::error('Error en TestQrGeneration', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class TestQrGeneration extends Command
{
    protected $signature = 'test:qr-generation {tramite_id=13}';
    protected $description = 'Probar la generación del QR independientemente';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Probando generación de QR para el trámite ID: {$tramiteId}");
        
        try {
            // Buscar el trámite
            $tramite = Tramite::with(['proveedor'])->find($tramiteId);
            
            if (!$tramite) {
                $this->error("❌ No se encontró el trámite con ID: {$tramiteId}");
                return 1;
            }
            
            $this->info("✅ Trámite encontrado: {$tramite->proveedor->razon_social}");
            
            // Crear instancia del servicio
            $oficioService = new OficioService();
            
            // Generar QR usando reflexión para acceder al método privado
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarQrCode');
            $method->setAccessible(true);
            
            $this->info("🔄 Generando QR...");
            $qrCode = $method->invoke($oficioService, $tramite);
            
            $this->info("📱 QR Code generado:");
            $this->line("   - Longitud: " . strlen($qrCode) . " caracteres");
            $this->line("   - Contiene HTML: " . (strpos($qrCode, '<') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene img tag: " . (strpos($qrCode, '<img') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene PNG: " . (strpos($qrCode, '.png') !== false ? 'Sí' : 'No'));
            
            // Mostrar el contenido del QR
            $this->info("📄 Contenido del QR:");
            $this->line($qrCode);
            
            // Verificar si el archivo QR existe
            $qrDirectory = storage_path('app/public/qr-codes');
            if (file_exists($qrDirectory)) {
                $this->info("✅ Directorio QR existe: {$qrDirectory}");
                $files = scandir($qrDirectory);
                $this->line("   - Archivos en el directorio: " . count($files) - 2);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $this->line("     - {$file}");
                    }
                }
            } else {
                $this->warn("⚠️ Directorio QR no existe: {$qrDirectory}");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar QR: " . $e->getMessage());
            Log::error('Error en TestQrGeneration', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class TestQrGeneration extends Command
{
    protected $signature = 'test:qr-generation {tramite_id=13}';
    protected $description = 'Probar la generación del QR independientemente';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("🔍 Probando generación de QR para el trámite ID: {$tramiteId}");
        
        try {
            // Buscar el trámite
            $tramite = Tramite::with(['proveedor'])->find($tramiteId);
            
            if (!$tramite) {
                $this->error("❌ No se encontró el trámite con ID: {$tramiteId}");
                return 1;
            }
            
            $this->info("✅ Trámite encontrado: {$tramite->proveedor->razon_social}");
            
            // Crear instancia del servicio
            $oficioService = new OficioService();
            
            // Generar QR usando reflexión para acceder al método privado
            $reflection = new \ReflectionClass($oficioService);
            $method = $reflection->getMethod('generarQrCode');
            $method->setAccessible(true);
            
            $this->info("🔄 Generando QR...");
            $qrCode = $method->invoke($oficioService, $tramite);
            
            $this->info("📱 QR Code generado:");
            $this->line("   - Longitud: " . strlen($qrCode) . " caracteres");
            $this->line("   - Contiene HTML: " . (strpos($qrCode, '<') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene img tag: " . (strpos($qrCode, '<img') !== false ? 'Sí' : 'No'));
            $this->line("   - Contiene PNG: " . (strpos($qrCode, '.png') !== false ? 'Sí' : 'No'));
            
            // Mostrar el contenido del QR
            $this->info("📄 Contenido del QR:");
            $this->line($qrCode);
            
            // Verificar si el archivo QR existe
            $qrDirectory = storage_path('app/public/qr-codes');
            if (file_exists($qrDirectory)) {
                $this->info("✅ Directorio QR existe: {$qrDirectory}");
                $files = scandir($qrDirectory);
                $this->line("   - Archivos en el directorio: " . count($files) - 2);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $this->line("     - {$file}");
                    }
                }
            } else {
                $this->warn("⚠️ Directorio QR no existe: {$qrDirectory}");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar QR: " . $e->getMessage());
            Log::error('Error en TestQrGeneration', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
} 