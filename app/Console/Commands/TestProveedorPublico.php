<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\Tramite;

class TestProveedorPublico extends Command
{
    protected $signature = 'test:proveedor-publico {proveedor_id}';
    protected $description = 'Probar la vista pública del proveedor';

    public function handle()
    {
        $proveedorId = $this->argument('proveedor_id');
        
        $this->info("🔍 Probando vista pública del proveedor ID: {$proveedorId}");
        
        // Buscar el proveedor
        $proveedor = Proveedor::with(['tramites.datosGenerales', 'tramites.actividades', 'tramites.direcciones'])->find($proveedorId);
        
        if (!$proveedor) {
            $this->error("❌ Proveedor no encontrado con ID: {$proveedorId}");
            return 1;
        }
        
        $this->info("✅ Proveedor encontrado:");
        $this->line("   - ID: {$proveedor->id}");
        $this->line("   - RFC: {$proveedor->rfc}");
        $this->line("   - Razón Social: {$proveedor->razon_social}");
        $this->line("   - PV: {$proveedor->numero_pv}");
        $this->line("   - Estado: {$proveedor->estado}");
        
        // Buscar el trámite más reciente
        $tramiteReciente = $proveedor->tramites()->latest()->first();
        
        if ($tramiteReciente) {
            $this->info("📋 Trámite más reciente:");
            $this->line("   - ID: {$tramiteReciente->id}");
            $this->line("   - Tipo: {$tramiteReciente->tipo_tramite}");
            $this->line("   - Status: {$tramiteReciente->status}");
            
            $datosGenerales = $tramiteReciente->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $this->info("📄 Datos Generales:");
                $this->line("   - Razón Social: {$datosGenerales->razon_social}");
                $this->line("   - Tipo Persona: {$datosGenerales->tipo_persona}");
            }
        }
        
        $this->info("🌐 URL de la vista pública:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        $this->info("📱 URL del QR:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        return 0;
    }
} 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\Tramite;

class TestProveedorPublico extends Command
{
    protected $signature = 'test:proveedor-publico {proveedor_id}';
    protected $description = 'Probar la vista pública del proveedor';

    public function handle()
    {
        $proveedorId = $this->argument('proveedor_id');
        
        $this->info("🔍 Probando vista pública del proveedor ID: {$proveedorId}");
        
        // Buscar el proveedor
        $proveedor = Proveedor::with(['tramites.datosGenerales', 'tramites.actividades', 'tramites.direcciones'])->find($proveedorId);
        
        if (!$proveedor) {
            $this->error("❌ Proveedor no encontrado con ID: {$proveedorId}");
            return 1;
        }
        
        $this->info("✅ Proveedor encontrado:");
        $this->line("   - ID: {$proveedor->id}");
        $this->line("   - RFC: {$proveedor->rfc}");
        $this->line("   - Razón Social: {$proveedor->razon_social}");
        $this->line("   - PV: {$proveedor->numero_pv}");
        $this->line("   - Estado: {$proveedor->estado}");
        
        // Buscar el trámite más reciente
        $tramiteReciente = $proveedor->tramites()->latest()->first();
        
        if ($tramiteReciente) {
            $this->info("📋 Trámite más reciente:");
            $this->line("   - ID: {$tramiteReciente->id}");
            $this->line("   - Tipo: {$tramiteReciente->tipo_tramite}");
            $this->line("   - Status: {$tramiteReciente->status}");
            
            $datosGenerales = $tramiteReciente->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $this->info("📄 Datos Generales:");
                $this->line("   - Razón Social: {$datosGenerales->razon_social}");
                $this->line("   - Tipo Persona: {$datosGenerales->tipo_persona}");
            }
        }
        
        $this->info("🌐 URL de la vista pública:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        $this->info("📱 URL del QR:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        return 0;
    }
} 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\Tramite;

class TestProveedorPublico extends Command
{
    protected $signature = 'test:proveedor-publico {proveedor_id}';
    protected $description = 'Probar la vista pública del proveedor';

    public function handle()
    {
        $proveedorId = $this->argument('proveedor_id');
        
        $this->info("🔍 Probando vista pública del proveedor ID: {$proveedorId}");
        
        // Buscar el proveedor
        $proveedor = Proveedor::with(['tramites.datosGenerales', 'tramites.actividades', 'tramites.direcciones'])->find($proveedorId);
        
        if (!$proveedor) {
            $this->error("❌ Proveedor no encontrado con ID: {$proveedorId}");
            return 1;
        }
        
        $this->info("✅ Proveedor encontrado:");
        $this->line("   - ID: {$proveedor->id}");
        $this->line("   - RFC: {$proveedor->rfc}");
        $this->line("   - Razón Social: {$proveedor->razon_social}");
        $this->line("   - PV: {$proveedor->numero_pv}");
        $this->line("   - Estado: {$proveedor->estado}");
        
        // Buscar el trámite más reciente
        $tramiteReciente = $proveedor->tramites()->latest()->first();
        
        if ($tramiteReciente) {
            $this->info("📋 Trámite más reciente:");
            $this->line("   - ID: {$tramiteReciente->id}");
            $this->line("   - Tipo: {$tramiteReciente->tipo_tramite}");
            $this->line("   - Status: {$tramiteReciente->status}");
            
            $datosGenerales = $tramiteReciente->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $this->info("📄 Datos Generales:");
                $this->line("   - Razón Social: {$datosGenerales->razon_social}");
                $this->line("   - Tipo Persona: {$datosGenerales->tipo_persona}");
            }
        }
        
        $this->info("🌐 URL de la vista pública:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        $this->info("📱 URL del QR:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        return 0;
    }
} 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\Tramite;

class TestProveedorPublico extends Command
{
    protected $signature = 'test:proveedor-publico {proveedor_id}';
    protected $description = 'Probar la vista pública del proveedor';

    public function handle()
    {
        $proveedorId = $this->argument('proveedor_id');
        
        $this->info("🔍 Probando vista pública del proveedor ID: {$proveedorId}");
        
        // Buscar el proveedor
        $proveedor = Proveedor::with(['tramites.datosGenerales', 'tramites.actividades', 'tramites.direcciones'])->find($proveedorId);
        
        if (!$proveedor) {
            $this->error("❌ Proveedor no encontrado con ID: {$proveedorId}");
            return 1;
        }
        
        $this->info("✅ Proveedor encontrado:");
        $this->line("   - ID: {$proveedor->id}");
        $this->line("   - RFC: {$proveedor->rfc}");
        $this->line("   - Razón Social: {$proveedor->razon_social}");
        $this->line("   - PV: {$proveedor->numero_pv}");
        $this->line("   - Estado: {$proveedor->estado}");
        
        // Buscar el trámite más reciente
        $tramiteReciente = $proveedor->tramites()->latest()->first();
        
        if ($tramiteReciente) {
            $this->info("📋 Trámite más reciente:");
            $this->line("   - ID: {$tramiteReciente->id}");
            $this->line("   - Tipo: {$tramiteReciente->tipo_tramite}");
            $this->line("   - Status: {$tramiteReciente->status}");
            
            $datosGenerales = $tramiteReciente->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $this->info("📄 Datos Generales:");
                $this->line("   - Razón Social: {$datosGenerales->razon_social}");
                $this->line("   - Tipo Persona: {$datosGenerales->tipo_persona}");
            }
        }
        
        $this->info("🌐 URL de la vista pública:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        $this->info("📱 URL del QR:");
        $this->line("   http://127.0.0.1:8000/proveedores/publico/{$proveedorId}");
        
        return 0;
    }
} 