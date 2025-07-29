<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use Illuminate\Console\Command;

class VerificarDatosPdf extends Command
{
    protected $signature = 'verificar:datos-pdf {oficio_id}';
    protected $description = 'Verificar qué datos se están pasando al PDF';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $this->info("=== VERIFICACIÓN DE DATOS PARA PDF ===");
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

        $this->info("\n=== DATOS DEL TRÁMITE ===");
        $this->info("ID: {$tramite->id}");
        $this->info("Estado: {$tramite->estado}");
        $this->info("Tipo: {$tramite->tipo_tramite}");
        $this->info("Fecha inicio: " . ($tramite->fecha_inicio ? $tramite->fecha_inicio->format('d/m/Y') : 'NULL'));

        $this->info("\n=== DATOS DEL PROVEEDOR ===");
        if ($proveedor) {
            $this->info("ID: {$proveedor->id}");
            $this->info("RFC: {$proveedor->rfc}");
            $this->info("PV: " . ($proveedor->pv ?: 'NULL'));
            $this->info("Fecha vencimiento: " . ($proveedor->fecha_vencimiento ? $proveedor->fecha_vencimiento->format('d/m/Y') : 'NULL'));
        } else {
            $this->error("❌ Proveedor es NULL");
        }

        $this->info("\n=== DATOS GENERALES ===");
        if ($datosGenerales) {
            $this->info("Razón Social: " . ($datosGenerales->razon_social ?: 'NULL'));
            $this->info("Giro: " . ($datosGenerales->giro ?: 'NULL'));
            $this->info("RFC: " . ($datosGenerales->rfc ?: 'NULL'));
        } else {
            $this->error("❌ Datos Generales es NULL");
        }

        $this->info("\n=== DATOS CONSTITUTIVOS ===");
        if ($datosConstitutivos) {
            $this->info("Instrumento: " . ($datosConstitutivos->instrumento_notarial ?: 'NULL'));
            $this->info("Fecha: " . ($datosConstitutivos->fecha_instrumento ? $datosConstitutivos->fecha_instrumento->format('d/m/Y') : 'NULL'));
            
            if ($datosConstitutivos->representanteLegal) {
                $this->info("Representante Legal: " . $datosConstitutivos->representanteLegal->nombre_completo);
            } else {
                $this->warn("⚠️ No hay representante legal");
            }
        } else {
            $this->error("❌ Datos Constitutivos es NULL");
        }

        $this->info("\n=== DIRECCIONES ===");
        if ($direcciones && $direcciones->count() > 0) {
            foreach ($direcciones as $index => $direccion) {
                $this->info("Dirección " . ($index + 1) . ":");
                $this->info("  Calle: " . ($direccion->calle ?: 'NULL'));
                $this->info("  Número: " . ($direccion->numero_exterior ?: 'NULL'));
                $this->info("  Colonia: " . ($direccion->colonia_asentamiento ?: 'NULL'));
                $this->info("  Municipio: " . ($direccion->municipio ?: 'NULL'));
                $this->info("  Estado: " . ($direccion->estado ? $direccion->estado->nombre : 'NULL'));
                $this->info("  C.P.: " . ($direccion->codigo_postal ?: 'NULL'));
            }
        } else {
            $this->error("❌ No hay direcciones");
        }

        $this->info("\n=== SIMULACIÓN DE DOMICILIO CONCATENADO ===");
        if ($direcciones && $direcciones->count() > 0) {
            $direccion = $direcciones->first();
            $domicilioCompleto = '';
            if ($direccion->calle) $domicilioCompleto .= $direccion->calle . ' ';
            if ($direccion->numero_exterior) $domicilioCompleto .= 'NÚMERO EXTERIOR ' . $direccion->numero_exterior . ' ';
            if ($direccion->numero_interior) $domicilioCompleto .= 'NÚMERO INTERIOR ' . $direccion->numero_interior . ' ';
            if ($direccion->colonia_asentamiento) $domicilioCompleto .= 'COL. ' . $direccion->colonia_asentamiento . ', ';
            if ($direccion->municipio) $domicilioCompleto .= $direccion->municipio . ', ';
            if ($direccion->estado && $direccion->estado->nombre) $domicilioCompleto .= $direccion->estado->nombre . ', ';
            if ($direccion->codigo_postal) $domicilioCompleto .= 'C.P. ' . $direccion->codigo_postal;
            $domicilioCompleto = trim($domicilioCompleto, ', ');
            
            $this->info("Domicilio concatenado: " . strtoupper($domicilioCompleto));
        }

        $this->info("\n=== VARIABLES QUE SE PASAN A LA VISTA ===");
        $this->info("proveedor: " . ($proveedor ? 'SÍ' : 'NO'));
        $this->info("datosGenerales: " . ($datosGenerales ? 'SÍ' : 'NO'));
        $this->info("datosConstitutivos: " . ($datosConstitutivos ? 'SÍ' : 'NO'));
        $this->info("direcciones: " . ($direcciones && $direcciones->count() > 0 ? 'SÍ' : 'NO'));
        $this->info("fechaInicioTramite: " . ($tramite->fecha_inicio ? 'SÍ' : 'NO'));
        $this->info("fechaGeneracionDocumento: " . ($oficio->fecha_oficio ? 'SÍ' : 'NO'));
        $this->info("fechaVigenciaProveedor: " . ($proveedor && $proveedor->fecha_vencimiento ? 'SÍ' : 'NO'));
    }
} 