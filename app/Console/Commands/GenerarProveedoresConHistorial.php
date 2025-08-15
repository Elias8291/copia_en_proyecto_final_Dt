<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\DatoGeneral;
use App\Models\Direccion;
use App\Models\Contacto;
use App\Models\Accionista;
use App\Models\ApoderadoLegal;
use App\Models\DatoConstitutivo;
use App\Models\InstrumentoNotarial;
use App\Models\Coordenada;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GenerarProveedoresConHistorial extends Command
{
    protected $signature = 'generar:proveedores-historial {cantidad=10 : Número de proveedores a generar} {--tramites-min=2 : Mínimo trámites por proveedor} {--tramites-max=5 : Máximo trámites por proveedor}';
    protected $description = 'Genera proveedores con múltiples trámites para probar el historial';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');
        $tramitesMin = (int) $this->option('tramites-min');
        $tramitesMax = (int) $this->option('tramites-max');

        if ($cantidad <= 0 || $cantidad > 100) {
            $this->error('La cantidad debe estar entre 1 y 100');
            return 1;
        }

        if ($tramitesMin < 2 || $tramitesMax < $tramitesMin) {
            $this->error('tramites-min debe ser al menos 2 y tramites-max debe ser mayor o igual a tramites-min');
            return 1;
        }

        $this->info("Generando {$cantidad} proveedores con historial de trámites...");
        $this->info("Cada proveedor tendrá entre {$tramitesMin} y {$tramitesMax} trámites");

        $faker = Faker::create('es_MX');
        $actividades = DB::table('actividad')->get();
        $estados = DB::table('estados')->get();

        $bar = $this->output->createProgressBar($cantidad);
        $bar->start();

        for ($i = 0; $i < $cantidad; $i++) {
            DB::beginTransaction();
            
            try {
                // Crear proveedor
                $tipoPersona = $faker->randomElement(['Física', 'Moral']);
                $proveedor = $this->crearProveedor($faker, $tipoPersona);
                
                // Crear múltiples trámites para este proveedor
                $numTramites = $faker->numberBetween($tramitesMin, $tramitesMax);
                $this->crearTramitesHistorial($proveedor, $numTramites, $faker, $actividades, $estados);
                
                DB::commit();
                $bar->advance();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("\nError creando proveedor " . ($i + 1) . ": " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ {$cantidad} proveedores con historial generados exitosamente!");
        
        // Mostrar estadísticas
        $this->mostrarEstadisticas();
    }

    private function crearProveedor($faker, $tipoPersona)
    {
        if ($tipoPersona === 'Física') {
            $nombres = ['José Luis', 'María Elena', 'Carlos Alberto', 'Ana Patricia', 'Francisco Javier'];
            $apellidos = ['García López', 'Rodríguez Martínez', 'González Hernández', 'Fernández Jiménez'];
            $razonSocial = $faker->randomElement($nombres) . ' ' . $faker->randomElement($apellidos);
        } else {
            $tipos = ['Constructora', 'Comercializadora', 'Servicios', 'Desarrollo', 'Tecnología'];
            $sectores = ['del Norte', 'Mexicana', 'Internacional', 'Profesional', 'Moderna'];
            $formas = ['S.A. de C.V.', 'S. de R.L.', 'S.C.'];
            
            $razonSocial = $faker->randomElement($tipos) . ' ' . 
                          $faker->randomElement($sectores) . ' ' . 
                          $faker->randomElement($formas);
        }

        // Fecha de alta en años pasados (2022-2023) para generar historial realista
        $añoAlta = $faker->randomElement([2022, 2023]);
        $fechaAlta = Carbon::create($añoAlta, $faker->numberBetween(1, 12), $faker->numberBetween(1, 28));
        
        return Proveedor::create([
            'usuario_id' => null,
            'pv_numero' => 'PV' . str_pad($faker->unique()->numberBetween(100000, 999999), 6, '0', STR_PAD_LEFT),
            'rfc' => $this->generateRFC($tipoPersona),
            'razon_social' => $razonSocial,
            'tipo_persona' => $tipoPersona,
            'estado_padron' => 'Activo', // Activo para que sea más realista
            'fecha_alta_padron' => $fechaAlta,
            'fecha_vencimiento_padron' => Carbon::now()->addYear()
        ]);
    }

    private function crearTramitesHistorial($proveedor, $numTramites, $faker, $actividades, $estados)
    {
        $tiposTramite = ['Inscripcion', 'Renovacion', 'Actualizacion'];
        $statusTramites = ['Aprobado', 'Rechazado', 'Revision_Digital', 'Pendiente'];
        
        // El primer trámite debe ser del año de fecha_alta_padron
        $añoAltaPadron = $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->year : 2022;
        $fechaBase = Carbon::create($añoAltaPadron, $faker->numberBetween(1, 12), $faker->numberBetween(1, 28));
        
        for ($t = 0; $t < $numTramites; $t++) {
            // Espaciar trámites cada 4-8 meses para que se vean más reales
            $mesesAgregar = $t * $faker->numberBetween(4, 8);
            $fechaInicio = $fechaBase->copy()->addMonths($mesesAgregar);
            
            // El primer trámite siempre es inscripción, los demás pueden variar
            $tipoTramite = $t === 0 ? 'Inscripcion' : $faker->randomElement($tiposTramite);
            
            // Estados más realistas según la antigüedad
            $añoTramite = $fechaInicio->year;
            if ($añoTramite <= 2022) {
                // Trámites muy antiguos: casi todos finalizados
                $status = $faker->randomElement(['Aprobado', 'Aprobado', 'Aprobado', 'Aprobado', 'Rechazado']);
            } elseif ($añoTramite == 2023) {
                // Trámites de 2023: mayormente finalizados
                $status = $faker->randomElement(['Aprobado', 'Aprobado', 'Aprobado', 'Rechazado', 'Rechazado']);
            } else {
                // Trámites de 2024: algunos en proceso
                if ($t === $numTramites - 1) {
                    // Último trámite: más probabilidad de estar en proceso
                    $status = $faker->randomElement(['Aprobado', 'Aprobado', 'Revision_Digital', 'Pendiente']);
                } else {
                    $status = $faker->randomElement(['Aprobado', 'Aprobado', 'Rechazado']);
                }
            }

            $tramite = Tramite::create([
                'proveedor_id' => $proveedor->id,
                'tipo_tramite' => $tipoTramite,
                'status' => $status,
                'fecha_inicio' => $fechaInicio,
                'fecha_finalizacion' => $status === 'Aprobado' ? $fechaInicio->copy()->addDays($faker->numberBetween(15, 45)) : null,
                'observaciones' => $this->generarObservaciones($status, $tipoTramite, $t + 1, $fechaInicio->year),
                'paso_actual' => $status === 'Aprobado' ? 5 : $faker->numberBetween(1, 4)
            ]);

            // Solo crear datos completos para algunos trámites (no todos para optimizar)
            if ($t === 0 || $t === $numTramites - 1 || $faker->boolean(30)) {
                $this->crearDatosTramite($tramite, $proveedor, $faker, $actividades, $estados);
            }
        }
    }

    private function generarObservaciones($status, $tipoTramite, $numeroTramite, $año)
    {
        $observaciones = [
            'Aprobado' => [
                "Trámite de {$tipoTramite} #{$numeroTramite} ({$año}) aprobado sin observaciones",
                "Documentación completa y correcta para {$tipoTramite} - Año {$año}",
                "Proceso de {$tipoTramite} finalizado exitosamente en {$año}"
            ],
            'Rechazado' => [
                "Trámite de {$tipoTramite} #{$numeroTramite} ({$año}) rechazado por documentación incompleta",
                "Falta información en datos constitutivos - Proceso {$año}",
                "RFC no coincide con documentación presentada - {$año}"
            ],
            'Revision_Digital' => [
                "Trámite de {$tipoTramite} #{$numeroTramite} ({$año}) en revisión digital",
                "Pendiente validación de documentos - {$año}",
                "En proceso de verificación desde {$año}"
            ],
            'Pendiente' => [
                "Trámite de {$tipoTramite} #{$numeroTramite} ({$año}) recibido, pendiente de revisión",
                "Documentación recibida en {$año}, en cola de procesamiento",
                "Esperando asignación de revisor - {$año}"
            ]
        ];

        return $observaciones[$status][array_rand($observaciones[$status])];
    }

    private function crearDatosTramite($tramite, $proveedor, $faker, $actividades, $estados)
    {
        // Datos generales
        DatoGeneral::create([
            'tramite_id' => $tramite->id,
            'curp' => $proveedor->tipo_persona === 'Física' ? $this->generateCURP() : null,
            'razon_social' => $proveedor->razon_social,
            'pagina_web' => $faker->optional(0.6)->url,
            'telefono' => $this->generatePhoneNumber(),
            'proveedor_id' => $proveedor->id,
            'status' => 'pendiente'
        ]);

        // Dirección
        $estado = $estados->isNotEmpty() ? $estados->random() : null;
        $coordenada = Coordenada::create([
            'latitud' => $faker->latitude(14.5, 32.5),
            'longitud' => $faker->longitude(-117.1, -86.7)
        ]);

        Direccion::create([
            'proveedor_id' => $proveedor->id,
            'tramite_id' => $tramite->id,
            'calle' => $faker->randomElement(['Av. Juárez', 'Calle Hidalgo', 'Av. Reforma']),
            'numero_exterior' => $faker->numberBetween(100, 9999),
            'colonia' => $faker->citySuffix,
            'codigo_postal' => $faker->postcode,
            'municipio' => 'Oaxaca de Juárez',
            'asentamiento' => $faker->citySuffix,
            'coordenada_id' => $coordenada->id,
            'estado_id' => $estado->id ?? 20,
            'status' => 'pendiente'
        ]);

        // Contacto
        Contacto::create([
            'nombre_contacto' => $proveedor->tipo_persona === 'Física' 
                ? $proveedor->razon_social 
                : $faker->name,
            'cargo' => $proveedor->tipo_persona === 'Física' 
                ? 'Propietario' 
                : $faker->randomElement(['Gerente General', 'Director', 'Administrador']),
            'correo_electronico' => $faker->unique()->safeEmail,
            'telefono' => $this->generatePhoneNumber(),
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'status' => 'pendiente'
        ]);

        // Actividades
        if ($actividades->isNotEmpty()) {
            $numActividades = $faker->numberBetween(1, 2);
            $actividadesSeleccionadas = $actividades->random($numActividades);
            
            foreach ($actividadesSeleccionadas as $actividad) {
                DB::table('actividades')->insert([
                    'proveedor_id' => $proveedor->id,
                    'tramite_id' => $tramite->id,
                    'actividad_id' => $actividad->id,
                    'status' => 'pendiente',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    private function mostrarEstadisticas()
    {
        $proveedoresConHistorial = DB::select("
            SELECT COUNT(*) as proveedores_con_historial 
            FROM proveedores p 
            WHERE (SELECT COUNT(*) FROM tramites t WHERE t.proveedor_id = p.id) >= 2
        ")[0]->proveedores_con_historial;

        $tramitesTotales = Tramite::count();
        $promedioTramites = $proveedoresConHistorial > 0 
            ? round($tramitesTotales / Proveedor::count(), 1) 
            : 0;

        $this->table(
            ['Estadística', 'Valor'],
            [
                ['Proveedores con historial (2+ trámites)', $proveedoresConHistorial],
                ['Total de trámites generados', $tramitesTotales],
                ['Promedio trámites por proveedor', $promedioTramites],
                ['Trámites Aprobados', Tramite::where('status', 'Aprobado')->count()],
                ['Trámites en Revisión', Tramite::where('status', 'Revision_Digital')->count()],
                ['Trámites Pendientes', Tramite::where('status', 'Pendiente')->count()],
                ['Trámites Rechazados', Tramite::where('status', 'Rechazado')->count()]
            ]
        );
    }

    private function generateRFC($tipoPersona): string
    {
        if ($tipoPersona === 'Física') {
            $letras = ['ABCD', 'EFGH', 'IJKL', 'MNOP', 'QRST'];
            $fecha = rand(700101, 991231);
            $homoclave = chr(rand(65, 90)) . chr(rand(65, 90)) . rand(0, 9);
            return $letras[array_rand($letras)] . $fecha . $homoclave;
        } else {
            $letras = ['ABC', 'DEF', 'GHI', 'JKL', 'MNO'];
            $fecha = rand(700101, 991231);
            $homoclave = chr(rand(65, 90)) . chr(rand(65, 90)) . rand(0, 9);
            return $letras[array_rand($letras)] . $fecha . $homoclave;
        }
    }

    private function generateCURP(): string
    {
        $letras = ['ABCD', 'EFGH', 'IJKL', 'MNOP', 'QRST'];
        $fecha = rand(700101, 991231);
        $sexo = ['H', 'M'][array_rand(['H', 'M'])];
        $estado = ['AS', 'BC', 'OC'][array_rand(['AS', 'BC', 'OC'])];
        $consonantes = ['BCD', 'FGH', 'JKL'][array_rand(['BCD', 'FGH', 'JKL'])];
        $digito = rand(0, 9);
        
        return $letras[array_rand($letras)] . $fecha . $sexo . $estado . $consonantes . $digito;
    }

    private function generatePhoneNumber(): string
    {
        $ladas = ['951', '55', '33', '81', '222'];
        $lada = $ladas[array_rand($ladas)];
        $numero = rand(1000000, 9999999);
        
        return $lada . $numero;
    }
}