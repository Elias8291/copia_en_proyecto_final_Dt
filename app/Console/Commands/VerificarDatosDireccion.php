<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;

class VerificarDatosDireccion extends Command
{
    protected $signature = 'verificar:datos-direccion {oficio_id}';
    protected $description = 'Verificar específicamente los datos de dirección';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $this->info("=== VERIFICACIÓN DE DATOS DE DIRECCIÓN ===");
        $this->info("Oficio ID: {$oficioId}");
        
        $oficio = Oficio::find($oficioId);
        if (!$oficio) {
            $this->error("No se encontró oficio con ID: {$oficioId}");
            return;
        }

        $tramite = $oficio->tramite;
        $tramite->load(['direcciones.estado']);

        $direcciones = $tramite->direcciones;

        $this->info("\n=== DATOS DE DIRECCIÓN EN BD ===");
        if ($direcciones && $direcciones->count() > 0) {
            foreach ($direcciones as $index => $direccion) {
                $this->info("Dirección " . ($index + 1) . ":");
                $this->info("  ID: {$direccion->id}");
                $this->info("  Calle: " . ($direccion->calle ?: 'NULL'));
                $this->info("  Número Exterior: " . ($direccion->numero_exterior ?: 'NULL'));
                $this->info("  Número Interior: " . ($direccion->numero_interior ?: 'NULL'));
                $this->info("  Colonia/Asentamiento: " . ($direccion->colonia_asentamiento ?: 'NULL'));
                $this->info("  Municipio: " . ($direccion->municipio ?: 'NULL'));
                $this->info("  Estado: " . ($direccion->estado ? $direccion->estado->nombre : 'NULL'));
                $this->info("  C.P.: " . ($direccion->codigo_postal ?: 'NULL'));
                $this->info("  Tipo Asentamiento: " . ($direccion->tipo_asentamiento ?: 'NULL'));
            }
        } else {
            $this->error("❌ No hay direcciones");
        }

        $this->info("\n=== SIMULACIÓN DE DOMICILIO EN LA VISTA ===");
        if ($direcciones && $direcciones->count() > 0) {
            $direccion = $direcciones->first();
            
            // Simular exactamente lo que hace la vista
            $domicilioCompleto = [];
            if ($direccion->calle) $domicilioCompleto[] = $direccion->calle;
            if ($direccion->numero_exterior) $domicilioCompleto[] = 'NÚMERO EXTERIOR ' . $direccion->numero_exterior;
            if ($direccion->numero_interior) $domicilioCompleto[] = 'NÚMERO INTERIOR ' . $direccion->numero_interior;
            if ($direccion->colonia_asentamiento) $domicilioCompleto[] = 'COL. ' . $direccion->colonia_asentamiento;
            if ($direccion->municipio) $domicilioCompleto[] = $direccion->municipio;
            if ($direccion->estado && $direccion->estado->nombre) $domicilioCompleto[] = $direccion->estado->nombre;
            if ($direccion->codigo_postal) $domicilioCompleto[] = 'C.P. ' . $direccion->codigo_postal;
            $domicilioString = implode(', ', $domicilioCompleto);
            
            $this->info("Domicilio generado: " . strtoupper($domicilioString));
        }

        $this->info("\n=== VERIFICACIÓN DE CONDICIONES ===");
        $condicion = isset($direcciones) && $direcciones->count() > 0;
        $this->info("Condición direcciones: " . ($condicion ? 'VERDADERO' : 'FALSO'));
        
        if ($condicion) {
            $direccion = $direcciones->first();
            $this->info("Calle existe: " . ($direccion->calle ? 'SÍ' : 'NO'));
            $this->info("Número exterior existe: " . ($direccion->numero_exterior ? 'SÍ' : 'NO'));
            $this->info("Número interior existe: " . ($direccion->numero_interior ? 'SÍ' : 'NO'));
            $this->info("Colonia existe: " . ($direccion->colonia_asentamiento ? 'SÍ' : 'NO'));
            $this->info("Municipio existe: " . ($direccion->municipio ? 'SÍ' : 'NO'));
            $this->info("Estado existe: " . ($direccion->estado ? 'SÍ' : 'NO'));
            $this->info("C.P. existe: " . ($direccion->codigo_postal ? 'SÍ' : 'NO'));
        }
    }
} 