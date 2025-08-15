<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\DatoGeneral;
use App\Models\Direccion;
use App\Models\Contacto;
use App\Models\Coordenada;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GenerarProveedoresRenovadores extends Command
{
    protected $signature = 'generar:proveedores-renovadores {cantidad=10 : Número de proveedores renovadores a generar}';
    protected $description = 'Genera proveedores con patrón de renovación constante año con año';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');

        if ($cantidad <= 0 || $cantidad > 50) {
            $this->error('La cantidad debe estar entre 1 y 50');
            return 1;
        }

        $this->info("Generando {$cantidad} proveedores renovadores constantes...");
        $this->info("Cada proveedor tendrá un patrón de renovación año con año desde 2022");

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
                
                // Crear patrón de renovación constante
                $this->crearPatronRenovacion($proveedor, $faker, $actividades, $estados);
                
                DB::commit();
                $bar->advance();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("\nError creando proveedor renovador " . ($i + 1) . ": " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ {$cantidad} proveedores renovadores generados exitosamente!");
        
        // Mostrar estadísticas
        $this->mostrarEstadisticasRenovadores();
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

        return Proveedor::create([
            'usuario_id' => null,
            'pv_numero' => 'PV' . str_pad($faker->unique()->numberBetween(100000, 999999), 6, '0', STR_PAD_LEFT),
            'rfc' => $this->generateRFC($tipoPersona),
            'razon_social' => $razonSocial,
            'tipo_persona' => $tipoPersona,
            'estado_padron' => 'Activo', // Los renovadores constantes están activos
            'fecha_alta_padron' => Carbon::create(2022, $faker->numberBetween(1, 6), $faker->numberBetween(1, 28)),
            'fecha_vencimiento_padron' => Carbon::now()->addYear()
        ]);
    }

    private function crearPatronRenovacion($proveedor, $faker, $actividades, $estados)
    {
        // Patrón típico de renovador constante:
        // 1. Inscripción inicial (año de fecha_alta_padron)
        // 2. Renovación cada año subsiguiente
        // Ocasionalmente actualizaciones entre renovaciones

        // Obtener el año de alta del padrón
        $añoInicial = $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->year : 2022;
        
        // Crear años desde el año inicial hasta 2025
        $años = range($añoInicial, 2025);
        $tramiteNum = 1;

        foreach ($años as $año) {
            // Fecha base para este año (entre enero y junio para renovaciones)
            $fechaBase = Carbon::create($año, $faker->numberBetween(1, 6), $faker->numberBetween(1, 28));

            if ($año === $añoInicial) {
                // Inscripción inicial en el año de alta del padrón
                $this->crearTramite($proveedor, 'Inscripcion', $fechaBase, 'Aprobado', $tramiteNum++, $faker, $actividades, $estados, true);
            } else {
                // Renovación anual
                $this->crearTramite($proveedor, 'Renovacion', $fechaBase, 'Aprobado', $tramiteNum++, $faker, $actividades, $estados);
                
                // 30% de probabilidad de actualización adicional en el mismo año
                if ($faker->boolean(30)) {
                    $fechaActualizacion = $fechaBase->copy()->addMonths($faker->numberBetween(3, 8));
                    $estadoActualizacion = $año === 2025 ? 
                        $faker->randomElement(['Aprobado', 'Revision_Digital', 'Pendiente']) :
                        'Aprobado';
                    
                    $this->crearTramite($proveedor, 'Actualizacion', $fechaActualizacion, $estadoActualizacion, $tramiteNum++, $faker, $actividades, $estados);
                }
            }
        }
    }

    private function crearTramite($proveedor, $tipoTramite, $fechaInicio, $status, $numero, $faker, $actividades, $estados, $crearDatos = false)
    {
        $observacion = $this->generarObservacionRenovador($tipoTramite, $status, $fechaInicio->year, $numero);
        
        $tramite = Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => $tipoTramite,
            'status' => $status,
            'fecha_inicio' => $fechaInicio,
            'fecha_finalizacion' => $status === 'Aprobado' ? $fechaInicio->copy()->addDays($faker->numberBetween(15, 30)) : null,
            'observaciones' => $observacion,
            'paso_actual' => $status === 'Aprobado' ? 5 : $faker->numberBetween(2, 4)
        ]);

        // Solo crear datos completos para el primer trámite o algunos aleatorios
        if ($crearDatos || $faker->boolean(25)) {
            $this->crearDatosTramite($tramite, $proveedor, $faker, $actividades, $estados);
        }

        return $tramite;
    }

    private function generarObservacionRenovador($tipoTramite, $status, $año, $numero)
    {
        $patrones = [
            'Inscripcion' => [
                'Aprobado' => "Inscripción inicial ({$año}) - Proveedor renovador constante #{$numero}",
            ],
            'Renovacion' => [
                'Aprobado' => "Renovación anual {$año} aprobada - Patrón constante #{$numero}",
            ],
            'Actualizacion' => [
                'Aprobado' => "Actualización {$año} completada - Mantenimiento de datos #{$numero}",
                'Revision_Digital' => "Actualización {$año} en revisión - Renovador habitual #{$numero}",
                'Pendiente' => "Actualización {$año} pendiente - Cliente recurrente #{$numero}",
            ]
        ];

        return $patrones[$tipoTramite][$status] ?? "Trámite de {$tipoTramite} #{$numero} ({$año}) - {$status}";
    }

    private function crearDatosTramite($tramite, $proveedor, $faker, $actividades, $estados)
    {
        // Datos generales
        DatoGeneral::create([
            'tramite_id' => $tramite->id,
            'curp' => $proveedor->tipo_persona === 'Física' ? $this->generateCURP() : null,
            'razon_social' => $proveedor->razon_social,
            'pagina_web' => $faker->optional(0.7)->url,
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

    private function mostrarEstadisticasRenovadores()
    {
        // Proveedores que califican como renovadores constantes
        $renovadores = DB::select("
            SELECT COUNT(*) as total
            FROM proveedores p 
            WHERE (
                SELECT COUNT(*) 
                FROM tramites t 
                WHERE t.proveedor_id = p.id 
                AND t.tipo_tramite = 'Renovacion'
            ) >= 2
            AND EXISTS (
                SELECT 1 
                FROM tramites t 
                WHERE t.proveedor_id = p.id 
                AND t.tipo_tramite = 'Inscripcion'
            )
        ")[0]->total;

        $renovacionesTotales = Tramite::where('tipo_tramite', 'Renovacion')->count();
        $inscripcionesTotales = Tramite::where('tipo_tramite', 'Inscripcion')->count();

        $this->table([
            'Estadística', 'Valor'
        ], [
            ['Proveedores Renovadores Constantes', $renovadores],
            ['Total Renovaciones en Sistema', $renovacionesTotales],
            ['Total Inscripciones en Sistema', $inscripcionesTotales],
            ['Promedio Renovaciones por Renovador', $renovadores > 0 ? round($renovacionesTotales / $renovadores, 1) : 0]
        ]);
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