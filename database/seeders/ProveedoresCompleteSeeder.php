<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use App\Models\User;
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

class ProveedoresCompleteSeeder extends Seeder
{
    private $count = 5;

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function run(): void
    {
        $this->command->info("Generando {$this->count} proveedores completos con sus trámites...");

        // Obtener datos necesarios de la base de datos - incrementar límites para soportar más proveedores
        $usuarios = User::limit(max(50, $this->count * 2))->get();
        $actividades = DB::table('actividad')->get(); // Obtener todas las actividades
        $estados = DB::table('estados')->get(); // Obtener todos los estados
        $municipios = DB::table('municipios')->get(); // Obtener todos los municipios
        $asentamientos = DB::table('asentamientos')->get(); // Obtener todos los asentamientos

        if ($usuarios->isEmpty()) {
            $this->command->error('No hay usuarios en la base de datos. Ejecuta primero UserSeeder.');
            return;
        }

        // Mostrar progreso para cantidades grandes
        if ($this->count > 100) {
            $this->command->info("Procesando en lotes para optimizar el rendimiento...");
        }

        $this->createProveedores($usuarios, $actividades, $estados, $municipios, $asentamientos);
    }

    private function createProveedores($usuarios, $actividades, $estados, $municipios, $asentamientos)
    {
        $faker = Faker::create('es_MX');
        
        $calles = [
            'Av. Juárez', 'Calle Hidalgo', 'Av. Insurgentes', 'Calle Morelos', 'Av. Reforma',
            'Calle Independencia', 'Av. Revolución', 'Calle Allende', 'Av. Universidad', 'Calle Madero',
            'Av. Constitución', 'Calle 5 de Mayo', 'Av. Libertad', 'Calle Francisco Villa', 'Av. López Mateos'
        ];

        $entre_calles = [
            ['5 de Mayo', 'Benito Juárez'],
            ['Lerdo de Tejada', 'Vicente Guerrero'],
            ['Francisco I. Madero', 'Venustiano Carranza'],
            ['Emiliano Zapata', 'Pancho Villa'],
            ['16 de Septiembre', 'Constitución'],
            ['Miguel Hidalgo', 'José María Morelos'],
            ['Ignacio Allende', 'Juan Aldama'],
            ['Lázaro Cárdenas', 'Adolfo López Mateos']
        ];

        // Procesar en lotes para mejor rendimiento
        $batchSize = 100;
        $batches = ceil($this->count / $batchSize);
        
        for ($batch = 0; $batch < $batches; $batch++) {
            $startIndex = $batch * $batchSize;
            $endIndex = min(($batch + 1) * $batchSize, $this->count);
            $currentBatchSize = $endIndex - $startIndex;
            
            if ($this->count > 100) {
                $this->command->info("Procesando lote " . ($batch + 1) . " de {$batches} ({$currentBatchSize} proveedores)...");
            }
            
            for ($i = $startIndex; $i < $endIndex; $i++) {
                DB::beginTransaction();
                
                try {
                    // Alternar tipo de persona: 40% Física, 60% Moral
                    $tipoPersona = $faker->randomElement(['Física', 'Física', 'Moral', 'Moral', 'Moral']);
                    $usuario = $usuarios->random();
                    
                    // Generar nombre/razón social más realista para Latinoamérica
                    if ($tipoPersona === 'Física') {
                        // Nombres latinoamericanos más realistas
                        $nombres = [
                            'José Luis', 'María Elena', 'Carlos Alberto', 'Ana Patricia', 'Francisco Javier',
                            'Guadalupe', 'Juan Carlos', 'Rosa María', 'Miguel Angel', 'Leticia',
                            'Roberto Carlos', 'Silvia Elena', 'Jorge Luis', 'Patricia', 'Ricardo',
                            'Carmen Rosa', 'Fernando', 'Claudia Patricia', 'Alejandro', 'Beatriz',
                            'Eduardo', 'Mónica', 'Raúl', 'Adriana', 'Sergio', 'Verónica',
                            'Daniel', 'Esperanza', 'Manuel', 'Rocío', 'Héctor', 'Norma',
                            'Arturo', 'Gloria', 'Enrique', 'Maricela', 'Gerardo', 'Sandra'
                        ];
                        
                        $apellidos = [
                            'García López', 'Rodríguez Martínez', 'González Hernández', 'Fernández Jiménez',
                            'López Ruiz', 'Martínez Morales', 'Sánchez Ortega', 'Pérez Mendoza',
                            'Gómez Castillo', 'Martín Vargas', 'Jiménez Ramos', 'Ruiz Guerrero',
                            'Hernández Torres', 'Díaz Flores', 'Moreno Silva', 'Muñoz Romero',
                            'Álvarez Cruz', 'Romero Herrera', 'Alonso Medina', 'Gutiérrez Aguilar',
                            'Navarro Delgado', 'Torres Moreno', 'Domínguez Vega', 'Vázquez Peña',
                            'Ramos Cortés', 'Gil Iglesias', 'Serrano Rubio', 'Blanco Marín',
                            'Molina Soto', 'Morales Castro', 'Ortega Ortiz', 'Delgado Ramírez'
                        ];
                        
                        $razonSocial = $faker->randomElement($nombres) . ' ' . $faker->randomElement($apellidos);
                    } else {
                        // Nombres de empresas más realistas para México/Latinoamérica
                        $tiposEmpresa = [
                            'Constructora', 'Comercializadora', 'Distribuidora', 'Servicios', 'Consultoría',
                            'Desarrollo', 'Ingeniería', 'Tecnología', 'Manufacturas', 'Corporativo',
                            'Grupo', 'Productos', 'Soluciones', 'Sistemas', 'Industrial'
                        ];
                        
                        $sectores = [
                            'del Norte', 'del Bajío', 'de Occidente', 'del Centro', 'del Pacífico',
                            'Mexicana', 'Latina', 'Internacional', 'Integral', 'Especializada',
                            'Profesional', 'Técnica', 'Avanzada', 'Moderna', 'Innovadora'
                        ];
                        
                        $complementos = [
                            'y Asociados', 'e Ingeniería', 'y Servicios', 'y Comercio', 'y Desarrollo',
                            'Especialistas', 'Profesionales', 'y Consultoría', 'y Tecnología', 'Integrales'
                        ];
                        
                        $formasJuridicas = ['S.A. de C.V.', 'S. de R.L. de C.V.', 'S.C.', 'S.A.', 'S. de R.L.'];
                        
                        $tipoEmpresa = $faker->randomElement($tiposEmpresa);
                        $sector = $faker->randomElement($sectores);
                        $complemento = $faker->optional(0.4)->randomElement($complementos); // 40% de probabilidad
                        $formaJuridica = $faker->randomElement($formasJuridicas);
                        
                        $razonSocial = $tipoEmpresa . ' ' . $sector . 
                                     ($complemento ? ' ' . $complemento : '') . 
                                     ' ' . $formaJuridica;
                    }
                    
                    // El estado del padrón se determinará por las fechas más adelante
                    
                    // Generar fechas más realistas para pruebas (2024)
                    $probabilidadVencido = 0.15; // 15% vencidos
                    $probabilidadAgosto2024 = 0.25; // 25% desde agosto 2024 (por vencer pronto)
                    // 60% restante serán desde septiembre 2024 en adelante
                    
                    $random = $faker->randomFloat(2, 0, 1);
                    
                    if ($random < $probabilidadVencido) {
                        // Proveedores vencidos (años pasados: 2022-2023)
                        $añoAlta = $faker->randomElement([2022, 2023]);
                        $fechaAlta = Carbon::create($añoAlta, $faker->numberBetween(1, 12), $faker->numberBetween(1, 28));
                        $fechaVencimiento = $fechaAlta->copy()->addYear();
                        $estadoPadron = $faker->randomElement(['Vencido', 'Vencido', 'Inactivo']);
                        
                    } elseif ($random < ($probabilidadVencido + $probabilidadAgosto2024)) {
                        // Proveedores activos desde agosto 2024 (vencen en agosto 2025 - algunos por vencer)
                        $fechaAlta = Carbon::create(2024, 8, $faker->numberBetween(1, 31));
                        $fechaVencimiento = $fechaAlta->copy()->addYear();
                        $estadoPadron = 'Activo';
                        
                    } else {
                        // Proveedores activos desde septiembre 2024 en adelante (para pruebas)
                        $mesInicio = $faker->randomElement([9, 10, 11, 12]); // Sept-Dic 2024
                        $fechaAlta = Carbon::create(2024, $mesInicio, $faker->numberBetween(1, 28));
                        $fechaVencimiento = $fechaAlta->copy()->addYear();
                        $estadoPadron = 'Activo';
                    }
                    
                    $proveedor = Proveedor::create([
                        'usuario_id' => null,
                        'pv_numero' => 'PV' . str_pad($faker->unique()->numberBetween(1000, 999999), 6, '0', STR_PAD_LEFT),
                        'rfc' => $this->generateRFC($tipoPersona),
                        'razon_social' => $razonSocial,
                        'tipo_persona' => $tipoPersona,
                        'estado_padron' => $estadoPadron,
                        'fecha_alta_padron' => $fechaAlta,
                        'fecha_vencimiento_padron' => $fechaVencimiento
                    ]);

                    // Crear trámite con estado coherente al estado del proveedor
                    $statusTramite = $estadoPadron === 'Activo' 
                        ? $faker->randomElement(['Aprobado', 'Aprobado', 'Aprobado', 'Revision_Digital']) // 75% aprobados
                        : ($estadoPadron === 'Pendiente' 
                            ? $faker->randomElement(['Pendiente', 'Revision_Digital', 'Pendiente']) // Mayormente pendientes
                            : $faker->randomElement(['Pendiente', 'Revision_Digital', 'Aprobado', 'Rechazado'])); // Mix para otros
                    
                    $tipoTramite = $estadoPadron === 'Activo' 
                        ? $faker->randomElement(['Inscripcion', 'Renovacion', 'Renovacion']) // Más renovaciones para activos
                        : $faker->randomElement(['Inscripcion', 'Renovacion', 'Actualizacion']);
                    
                    $tramite = Tramite::create([
                        'proveedor_id' => $proveedor->id,
                        'tipo_tramite' => $tipoTramite,
                        'status' => $statusTramite,
                        'fecha_inicio' => Carbon::now()->subDays($faker->numberBetween(1, 60)),
                        'observaciones' => 'Trámite generado automáticamente para pruebas',
                        'paso_actual' => $statusTramite === 'Aprobado' ? 5 : $faker->numberBetween(1, 4)
                    ]);

                    // Crear datos generales
                    DatoGeneral::create([
                        'tramite_id' => $tramite->id,
                        'curp' => $tipoPersona === 'Física' ? $this->generateCURP() : null,
                        'razon_social' => $proveedor->razon_social,
                        'pagina_web' => $faker->optional(0.7)->url,
                        'telefono' => $this->generatePhoneNumber(),
                        'proveedor_id' => $proveedor->id,
                        'status' => 'pendiente'
                    ]);

                    // Crear dirección
                    $estado = $estados->isNotEmpty() ? $estados->random() : null;
                    $municipio = $municipios->isNotEmpty() ? $municipios->random() : null;
                    $asentamiento = $asentamientos->isNotEmpty() ? $asentamientos->random() : null;
                    $calle = $faker->randomElement($calles);
                    $entreCalles = $faker->randomElement($entre_calles);

                    // Coordenadas específicas por estado mexicano (ajustadas para territorio terrestre real)
                    $coordenadasPorEstado = [
                        1 => ['nombre' => 'Aguascalientes', 'lat_min' => 21.70, 'lat_max' => 22.40, 'lng_min' => -102.90, 'lng_max' => -101.90],
                        2 => ['nombre' => 'Baja California', 'lat_min' => 28.85, 'lat_max' => 32.50, 'lng_min' => -117.10, 'lng_max' => -109.40],
                        3 => ['nombre' => 'Baja California Sur', 'lat_min' => 22.90, 'lat_max' => 28.00, 'lng_min' => -115.00, 'lng_max' => -109.20],
                        4 => ['nombre' => 'Campeche', 'lat_min' => 17.80, 'lat_max' => 20.70, 'lng_min' => -92.50, 'lng_max' => -89.10],
                        5 => ['nombre' => 'Chiapas', 'lat_min' => 14.50, 'lat_max' => 17.99, 'lng_min' => -94.10, 'lng_max' => -90.20],
                        6 => ['nombre' => 'Chihuahua', 'lat_min' => 25.50, 'lat_max' => 31.80, 'lng_min' => -109.10, 'lng_max' => -103.20],
                        7 => ['nombre' => 'Ciudad de México', 'lat_min' => 19.20, 'lat_max' => 19.59, 'lng_min' => -99.36, 'lng_max' => -98.94],
                        8 => ['nombre' => 'Coahuila', 'lat_min' => 24.50, 'lat_max' => 29.90, 'lng_min' => -104.70, 'lng_max' => -99.90],
                        9 => ['nombre' => 'Colima', 'lat_min' => 18.60, 'lat_max' => 19.56, 'lng_min' => -104.76, 'lng_max' => -103.40],
                        10 => ['nombre' => 'Durango', 'lat_min' => 22.30, 'lat_max' => 26.80, 'lng_min' => -107.15, 'lng_max' => -102.40],
                        11 => ['nombre' => 'Guanajuato', 'lat_min' => 19.95, 'lat_max' => 21.67, 'lng_min' => -102.10, 'lng_max' => -99.61],
                        12 => ['nombre' => 'Guerrero', 'lat_min' => 16.18, 'lat_max' => 18.87, 'lng_min' => -102.10, 'lng_max' => -98.00],
                        13 => ['nombre' => 'Hidalgo', 'lat_min' => 19.60, 'lat_max' => 21.40, 'lng_min' => -99.90, 'lng_max' => -97.85],
                        14 => ['nombre' => 'Jalisco', 'lat_min' => 18.93, 'lat_max' => 22.75, 'lng_min' => -105.70, 'lng_max' => -101.40],
                        15 => ['nombre' => 'Estado de México', 'lat_min' => 18.35, 'lat_max' => 20.42, 'lng_min' => -100.37, 'lng_max' => -98.60],
                        16 => ['nombre' => 'Michoacán', 'lat_min' => 18.11, 'lat_max' => 20.44, 'lng_min' => -103.77, 'lng_max' => -100.04],
                        17 => ['nombre' => 'Morelos', 'lat_min' => 18.35, 'lat_max' => 19.14, 'lng_min' => -99.59, 'lng_max' => -98.63],
                        18 => ['nombre' => 'Nayarit', 'lat_min' => 20.61, 'lat_max' => 23.08, 'lng_min' => -106.00, 'lng_max' => -103.73],
                        19 => ['nombre' => 'Nuevo León', 'lat_min' => 23.16, 'lat_max' => 27.78, 'lng_min' => -101.20, 'lng_max' => -98.84],
                        20 => ['nombre' => 'Oaxaca', 'lat_min' => 15.65, 'lat_max' => 18.66, 'lng_min' => -98.75, 'lng_max' => -93.95],
                        21 => ['nombre' => 'Puebla', 'lat_min' => 17.52, 'lat_max' => 20.85, 'lng_min' => -99.05, 'lng_max' => -96.43],
                        22 => ['nombre' => 'Querétaro', 'lat_min' => 20.01, 'lat_max' => 21.62, 'lng_min' => -100.51, 'lng_max' => -99.03],
                        23 => ['nombre' => 'Quintana Roo', 'lat_min' => 17.81, 'lat_max' => 21.61, 'lng_min' => -89.23, 'lng_max' => -86.74],
                        24 => ['nombre' => 'San Luis Potosí', 'lat_min' => 21.16, 'lat_max' => 24.55, 'lng_min' => -102.18, 'lng_max' => -98.30],
                        25 => ['nombre' => 'Sinaloa', 'lat_min' => 22.50, 'lat_max' => 26.93, 'lng_min' => -109.48, 'lng_max' => -105.39],
                        26 => ['nombre' => 'Sonora', 'lat_min' => 26.00, 'lat_max' => 32.50, 'lng_min' => -115.03, 'lng_max' => -108.40],
                        27 => ['nombre' => 'Tabasco', 'lat_min' => 17.33, 'lat_max' => 18.65, 'lng_min' => -94.79, 'lng_max' => -91.00],
                        28 => ['nombre' => 'Tamaulipas', 'lat_min' => 22.22, 'lat_max' => 27.67, 'lng_min' => -100.89, 'lng_max' => -97.14],
                        29 => ['nombre' => 'Tlaxcala', 'lat_min' => 19.06, 'lat_max' => 19.75, 'lng_min' => -98.76, 'lng_max' => -97.37],
                        30 => ['nombre' => 'Veracruz', 'lat_min' => 17.15, 'lat_max' => 22.46, 'lng_min' => -98.67, 'lng_max' => -93.55],
                        31 => ['nombre' => 'Yucatán', 'lat_min' => 19.50, 'lat_max' => 21.61, 'lng_min' => -90.52, 'lng_max' => -87.32],
                        32 => ['nombre' => 'Zacatecas', 'lat_min' => 21.04, 'lat_max' => 25.14, 'lng_min' => -104.42, 'lng_max' => -101.06]
                    ];
                    
                    // Dar mayor peso a Oaxaca (90% de probabilidad)
                    $oaxacaProbability = 0.9;
                    if ($faker->boolean($oaxacaProbability * 100)) {
                        $estadoId = 20; // Oaxaca
                    } else {
                        // Usar el estado seleccionado o uno aleatorio de los demás estados
                        $estadoId = $estado->id ?? array_rand($coordenadasPorEstado);
                    }
                    
                    $coordenadasEstado = $coordenadasPorEstado[$estadoId] ?? $coordenadasPorEstado[20]; // Default: Oaxaca
                    
                    // Si es Oaxaca, usar coordenadas de ciudades principales
                    if ($estadoId == 20) {
                        $ciudadesOaxaca = [
                            ['nombre' => 'Oaxaca de Juárez', 'lat' => 17.0732, 'lng' => -96.7266],
                            ['nombre' => 'Salina Cruz', 'lat' => 16.1647, 'lng' => -95.1903],
                            ['nombre' => 'Tuxtepec', 'lat' => 18.0951, 'lng' => -96.1267],
                            ['nombre' => 'Juchitán', 'lat' => 16.4347, 'lng' => -95.0122],
                            ['nombre' => 'Pochutla', 'lat' => 15.7425, 'lng' => -96.4669],
                            ['nombre' => 'Huajuapan de León', 'lat' => 17.8044, 'lng' => -97.7767],
                            ['nombre' => 'Puerto Escondido', 'lat' => 15.8539, 'lng' => -97.0700],
                            ['nombre' => 'Pinotepa Nacional', 'lat' => 16.3414, 'lng' => -98.0539],
                        ];
                        
                        $ciudadOaxaca = $faker->randomElement($ciudadesOaxaca);
                        // Generar coordenadas cerca de la ciudad seleccionada (radio ~10km)
                        $latitud = $ciudadOaxaca['lat'] + $faker->randomFloat(4, -0.1, 0.1);
                        $longitud = $ciudadOaxaca['lng'] + $faker->randomFloat(4, -0.1, 0.1);
                    } else {
                        // Para otros estados, usar los rangos definidos
                        $latitud = $faker->randomFloat(6, $coordenadasEstado['lat_min'], $coordenadasEstado['lat_max']);
                        $longitud = $faker->randomFloat(6, $coordenadasEstado['lng_min'], $coordenadasEstado['lng_max']);
                    }
                    
                    // Redondear a 8 decimales (precisión de la base de datos)
                    $latitud = round($latitud, 8);
                    $longitud = round($longitud, 8);

                    // Crear el registro de coordenadas
                    $coordenada = Coordenada::create([
                        'latitud' => $latitud,
                        'longitud' => $longitud
                    ]);

                    // Asegurar que el estado coincida con las coordenadas generadas
                    $estadoFinalId = $estadoId;
                    
                    Direccion::create([
                        'proveedor_id' => $proveedor->id,
                        'tramite_id' => $tramite->id,
                        'calle' => $calle,
                        'entre_calle' => $entreCalles[0],
                        'y_calle' => $entreCalles[1],
                        'numero_exterior' => $faker->numberBetween(100, 9999),
                        'numero_interior' => $faker->optional(0.3)->numberBetween(1, 50),
                        'colonia' => $asentamiento->nombre ?? $faker->citySuffix,
                        'codigo_postal' => $faker->postcode,
                        'municipio' => $estadoId == 20 ? 
                            $faker->randomElement(['Oaxaca de Juárez', 'Salina Cruz', 'San Juan Bautista Tuxtepec', 'Juchitán de Zaragoza', 'Santo Domingo Tehuantepec', 'Huajuapan de León', 'Puerto Escondido', 'Pinotepa Nacional', 'Miahuatlán de Porfirio Díaz', 'San Pedro Pochutla']) :
                            ($municipio->nombre ?? 'Aguascalientes'),
                        'asentamiento' => $asentamiento->nombre ?? $faker->citySuffix,
                        'coordenada_id' => $coordenada->id,
                        'estado_id' => $estadoFinalId,
                        'status' => 'pendiente'
                    ]);

                    // Crear contacto con nombres latinoamericanos
                    if ($tipoPersona === 'Física') {
                        $nombreContacto = $proveedor->razon_social;
                    } else {
                        $nombresContacto = [
                            'Ana Patricia Hernández', 'Carlos Eduardo Ramírez', 'María del Carmen Torres',
                            'Luis Fernando García', 'Rosa Elena Morales', 'Jorge Alberto Sánchez',
                            'Claudia Esperanza López', 'Ricardo Alejandro Vega', 'Gloria Beatriz Cruz',
                            'Miguel Ángel Flores', 'Patricia Guadalupe Ruiz', 'José Antonio Mendoza',
                            'Silvia Rocío Guerrero', 'Fernando Gabriel Ortega', 'Mónica Leticia Silva'
                        ];
                        $nombreContacto = $faker->randomElement($nombresContacto);
                    }
                    
                    Contacto::create([
                        'nombre_contacto' => $nombreContacto,
                        'cargo' => $tipoPersona === 'Física' 
                            ? 'Propietario' 
                            : $faker->randomElement(['Gerente General', 'Director', 'Administrador', 'Representante Legal', 'Coordinador']),
                        'correo_electronico' => $faker->unique()->safeEmail,
                        'telefono' => $this->generatePhoneNumber(),
                    'tramite_id' => $tramite->id,
                    'proveedor_id' => $proveedor->id,
                    'status' => 'pendiente'
                ]);

                    // Crear actividades (1-3 actividades por proveedor)
                    if ($actividades->isNotEmpty()) {
                        $numActividades = $faker->numberBetween(1, min(3, $actividades->count()));
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

                    // Crear accionistas (solo para personas morales)
                    if ($tipoPersona === 'Moral') {
                        $numAccionistas = $faker->numberBetween(2, 4);
                        $porcentajeRestante = 100.0;
                        
                        for ($j = 0; $j < $numAccionistas; $j++) {
                            $porcentaje = $j === $numAccionistas - 1 ? 
                                $porcentajeRestante : 
                                round($faker->randomFloat(2, 10, min(50, $porcentajeRestante - ($numAccionistas - $j - 1) * 5)), 2);
                            
                            $porcentajeRestante -= $porcentaje;
                            
                            // Nombres latinoamericanos para accionistas
                            $nombresAccionistas = [
                                'Roberto Carlos Mendoza', 'Elena Patricia Vázquez', 'José Manuel Herrera',
                                'Carmen Rosa Jiménez', 'Luis Eduardo Morales', 'Sandra Beatriz Torres',
                                'Alejandro Raúl Sánchez', 'Guadalupe María González', 'Fernando Javier López',
                                'Norma Esperanza Castillo', 'Arturo Miguel Ruiz', 'Verónica Silvia Ramos'
                            ];
                            
                            Accionista::create([
                                'nombre' => $faker->randomElement($nombresAccionistas),
                                'porcentaje_participacion' => $porcentaje,
                                'proveedor_id' => $proveedor->id,
                                'tramite_id' => $tramite->id,
                                'rfc' => $this->generateRFC('Física'),
                                'status' => 'pendiente'
                            ]);
                        }

                        // Nombres de notarios más realistas
                        $nombresNotarios = [
                            'Lic. Eduardo Ramírez Soto', 'Lic. María del Carmen Vega Herrera',
                            'Lic. José Antonio Morales García', 'Lic. Patricia Elena Cruz Mendoza',
                            'Lic. Fernando Gabriel López Torres', 'Lic. Rosa Esperanza Silva Ruiz',
                            'Lic. Carlos Manuel Guerrero Flores', 'Lic. Ana Beatriz Hernández Jiménez',
                            'Lic. Luis Alberto Castillo Ramos', 'Lic. Gloria Patricia Moreno Vázquez'
                        ];
                        
                        // Crear instrumento notarial para apoderado legal
                        $instrumentoApoderado = InstrumentoNotarial::create([
                            'numero_escritura' => $faker->numberBetween(1000, 9999),
                            'numero_escritura_constitutiva' => $faker->numberBetween(1000, 9999),
                            'fecha_constitucion' => Carbon::now()->subDays($faker->numberBetween(365, 3650)),
                            'nombre_notario' => $faker->randomElement($nombresNotarios),
                            'numero_notario' => $faker->numberBetween(1, 500),
                            'numero_registro_publico' => $faker->numberBetween(10000, 99999),
                            'fecha_inscripcion' => Carbon::now()->subDays($faker->numberBetween(15, 1000)),
                            'estado_id' => $estado->id ?? 1
                        ]);

                        // Crear apoderado legal con nombres latinoamericanos
                        $nombresApoderados = [
                            'Lic. María Elena Rodríguez Gómez', 'Lic. Carlos Alberto Hernández Silva',
                            'Lic. Ana Patricia López Morales', 'Lic. José Luis Martínez Torres',
                            'Lic. Rosa María González Vega', 'Lic. Fernando Javier Sánchez Cruz',
                            'Lic. Patricia Guadalupe Ruiz Flores', 'Lic. Miguel Ángel Pérez Guerrero',
                            'Lic. Claudia Esperanza Díaz Ramos', 'Lic. Ricardo Alejandro Moreno Castillo'
                        ];
                        
                        ApoderadoLegal::create([
                            'instrumento_notarial_id' => $instrumentoApoderado->id,
                            'nombre_apoderado' => $faker->randomElement($nombresApoderados),
                            'rfc' => $this->generateRFC('Física'),
                            'numero_escritura_constitutiva_poder' => $faker->numberBetween(1000, 9999),
                            'numero_registro_publico_poder' => $faker->numberBetween(10000, 99999),
                            'fecha_inscripcion_poder' => Carbon::now()->subDays($faker->numberBetween(15, 1000)),
                            'tramite_id' => $tramite->id,
                            'proveedor_id' => $proveedor->id,
                            'status' => 'pendiente'
                        ]);

                        // Crear instrumento notarial para constitución (diferente del apoderado)
                        $instrumentoConstitucion = InstrumentoNotarial::create([
                            'numero_escritura' => $faker->numberBetween(5000, 9999), // Diferente rango para constitución
                            'numero_escritura_constitutiva' => $faker->numberBetween(5000, 9999),
                            'fecha_constitucion' => Carbon::now()->subDays($faker->numberBetween(365, 3650)),
                            'nombre_notario' => $faker->randomElement($nombresNotarios), // Usar nombres realistas
                            'numero_notario' => $faker->numberBetween(501, 999), // Diferente rango
                            'numero_registro_publico' => $faker->numberBetween(50000, 99999), // Diferente rango
                            'fecha_inscripcion' => Carbon::now()->subDays($faker->numberBetween(300, 3500)),
                            'estado_id' => $estado->id ?? 1
                        ]);

                        // Crear datos constitutivos asociados al instrumento notarial de constitución
                        DatoConstitutivo::create([
                            'instrumento_notarial_id' => $instrumentoConstitucion->id,
                            'proveedor_id' => $proveedor->id,
                            'tramite_id' => $tramite->id,
                            'status' => 'pendiente'
                        ]);
                    }

                    DB::commit();
                    
                    // Solo mostrar progreso cada 10 proveedores si son muchos
                    if ($this->count <= 10 || ($i + 1) % 10 === 0) {
                        $this->command->info("Proveedor " . ($i + 1) . "/{$this->count}: {$proveedor->razon_social} creado exitosamente");
                    }
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->command->error("Error creando proveedor " . ($i + 1) . ": " . $e->getMessage());
                }
            }
        }
        
        $this->command->info("\n🎉 ¡Todos los proveedores han sido generados exitosamente!");
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
        $estado = ['AS', 'BC', 'BS', 'CC', 'CL'][array_rand(['AS', 'BC', 'BS', 'CC', 'CL'])];
        $consonantes = ['BCD', 'FGH', 'JKL'][array_rand(['BCD', 'FGH', 'JKL'])];
        $digito = rand(0, 9);
        
        return $letras[array_rand($letras)] . $fecha . $sexo . $estado . $consonantes . $digito;
    }

    private function generatePhoneNumber(): string
    {
        $ladas = ['449', '55', '33', '81', '222'];
        $lada = $ladas[array_rand($ladas)];
        $numero = rand(1000000, 9999999);
        
        return $lada . $numero;
    }

    private function generateContactName(): string
    {
        $nombres = ['Carlos', 'María', 'José', 'Ana', 'Luis', 'Carmen', 'Francisco', 'Elena'];
        $apellidos = ['García', 'Rodríguez', 'López', 'Martínez', 'González', 'Pérez', 'Sánchez', 'Ramírez'];
        
        $nombre = $nombres[array_rand($nombres)];
        $apellido1 = $apellidos[array_rand($apellidos)];
        $apellido2 = $apellidos[array_rand($apellidos)];
        
        return "$nombre $apellido1 $apellido2";
    }
}
