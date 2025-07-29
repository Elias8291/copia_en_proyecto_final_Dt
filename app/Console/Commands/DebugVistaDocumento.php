<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;

class DebugVistaDocumento extends Command
{
    protected $signature = 'debug:vista-documento {oficio_id}';
    protected $description = 'Depurar qué datos se pasan a la vista documento.blade.php';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $this->info("=== DEPURACIÓN DE VISTA DOCUMENTO ===");
        $this->info("Oficio ID: {$oficioId}");
        
        $oficio = Oficio::find($oficioId);
        if (!$oficio) {
            $this->error("No se encontró oficio con ID: {$oficioId}");
            return;
        }

        $tramite = $oficio->tramite;
        $tramite->load(['proveedor', 'datosGenerales', 'datosConstitutivos', 'direcciones.estado']);

        $proveedor = $tramite->proveedor;
        $datosGenerales = $tramite->datosGenerales;
        $datosConstitutivos = $tramite->datosConstitutivos;
        $direcciones = $tramite->direcciones;

        // Simular exactamente lo que hace el controlador
        $qrData = json_encode([
            'oficio_id' => $oficio->id,
            'numero_oficio' => $oficio->numero_oficio,
            'fecha_oficio' => $oficio->fecha_oficio->format('Y-m-d'),
            'tramite_id' => $tramite->id
        ]);

        $this->info("\n=== DATOS QUE SE PASAN A LA VISTA ===");
        $this->info("oficio: " . ($oficio ? 'SÍ' : 'NO'));
        $this->info("tramite: " . ($tramite ? 'SÍ' : 'NO'));
        $this->info("proveedor: " . ($proveedor ? 'SÍ' : 'NO'));
        $this->info("datosGenerales: " . ($datosGenerales ? 'SÍ' : 'NO'));
        $this->info("datosConstitutivos: " . ($datosConstitutivos ? 'SÍ' : 'NO'));
        $this->info("direcciones: " . ($direcciones && $direcciones->count() > 0 ? 'SÍ' : 'NO'));
        $this->info("qrCode: SÍ (generado)");
        $this->info("fechaTexto: " . ($oficio->fecha_oficio ? 'SÍ' : 'NO'));
        $this->info("detalleTramite: " . ($datosGenerales ? 'SÍ' : 'NO'));
        $this->info("solicitante: " . ($proveedor ? 'SÍ' : 'NO'));
        $this->info("fechaInicioTramite: " . ($tramite->fecha_inicio ? 'SÍ' : 'NO'));
        $this->info("fechaGeneracionDocumento: " . ($oficio->fecha_oficio ? 'SÍ' : 'NO'));
        $this->info("fechaVigenciaProveedor: " . ($proveedor && $proveedor->fecha_vencimiento ? 'SÍ' : 'NO'));
        $this->info("tipoTramite: SÍ (constante)");

        $this->info("\n=== VERIFICACIÓN DE CONDICIONES EN LA VISTA ===");
        
        // Verificar condición para representante legal
        $condicion1 = isset($datosConstitutivos) && $datosConstitutivos && isset($datosConstitutivos->representanteLegal) && $datosConstitutivos->representanteLegal && $datosConstitutivos->representanteLegal->nombre_completo;
        $this->info("Condición representante legal: " . ($condicion1 ? 'VERDADERO' : 'FALSO'));
        
        // Verificar condición para razón social
        $condicion2 = isset($datosGenerales) && $datosGenerales && $datosGenerales->razon_social;
        $this->info("Condición razón social: " . ($condicion2 ? 'VERDADERO' : 'FALSO'));
        
        // Verificar condición para direcciones
        $condicion3 = isset($direcciones) && $direcciones->count() > 0;
        $this->info("Condición direcciones: " . ($condicion3 ? 'VERDADERO' : 'FALSO'));
        
        // Verificar condición para RFC
        $condicion4 = isset($proveedor) && $proveedor && $proveedor->rfc;
        $this->info("Condición RFC: " . ($condicion4 ? 'VERDADERO' : 'FALSO'));

        $this->info("\n=== VALORES ESPECÍFICOS ===");
        if ($datosGenerales) {
            $this->info("Razón Social: " . ($datosGenerales->razon_social ?: 'NULL'));
            $this->info("Giro: " . ($datosGenerales->giro ?: 'NULL'));
        }
        
        if ($direcciones && $direcciones->count() > 0) {
            $direccion = $direcciones->first();
            $this->info("Calle: " . ($direccion->calle ?: 'NULL'));
            $this->info("Número: " . ($direccion->numero_exterior ?: 'NULL'));
            $this->info("Colonia: " . ($direccion->colonia_asentamiento ?: 'NULL'));
            $this->info("Municipio: " . ($direccion->municipio ?: 'NULL'));
            $this->info("Estado: " . ($direccion->estado ? $direccion->estado->nombre : 'NULL'));
            $this->info("C.P.: " . ($direccion->codigo_postal ?: 'NULL'));
        }
        
        if ($proveedor) {
            $this->info("RFC Proveedor: " . ($proveedor->rfc ?: 'NULL'));
        }

        $this->info("\n=== SIMULACIÓN DE DOMICILIO ===");
        if ($direcciones && $direcciones->count() > 0) {
            $direccion = $direcciones->first();
            $domicilioCompleto = [];
            if ($direccion->calle) $domicilioCompleto[] = $direccion->calle;
            if ($direccion->numero_exterior) $domicilioCompleto[] = 'NÚMERO EXTERIOR ' . $direccion->numero_exterior;
            if ($direccion->numero_interior) $domicilioCompleto[] = 'NÚMERO INTERIOR ' . $direccion->numero_interior;
            if ($direccion->colonia_asentamiento) $domicilioCompleto[] = 'COL. ' . $direccion->colonia_asentamiento;
            if ($direccion->municipio) $domicilioCompleto[] = $direccion->municipio;
            if ($direccion->estado && $direccion->estado->nombre) $domicilioCompleto[] = $direccion->estado->nombre;
            if ($direccion->codigo_postal) $domicilioCompleto[] = 'C.P. ' . $direccion->codigo_postal;
            $domicilioString = implode(', ', $domicilioCompleto);
            
            $this->info("Domicilio concatenado: " . strtoupper($domicilioString));
        }
    }
} 