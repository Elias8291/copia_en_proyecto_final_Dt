<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\User;
use Carbon\Carbon;

class CitasSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener algunos tramites y proveedores existentes
        $tramites = Tramite::take(10)->get();
        $proveedores = Proveedor::take(10)->get();
        $usuarios = User::take(5)->get();

        if ($tramites->isEmpty() || $proveedores->isEmpty()) {
            $this->command->info('No hay tramites o proveedores suficientes para crear citas. Creando datos básicos...');
            
            // Crear algunos proveedores si no existen
            if ($proveedores->isEmpty()) {
                $proveedores = collect([
                    Proveedor::create([
                        'rfc' => 'XAXX010101000',
                        'razon_social' => 'Empresa Demo S.A. de C.V.',
                        'nombre_comercial' => 'Empresa Demo',
                        'regimen_fiscal' => '601',
                        'codigo_postal' => '12345',
                        'telefono' => '5551234567',
                        'email' => 'contacto@empresademo.com'
                    ]),
                    Proveedor::create([
                        'rfc' => 'YAYY020202000',
                        'razon_social' => 'Servicios Integrales S.C.',
                        'nombre_comercial' => 'Servicios Integrales',
                        'regimen_fiscal' => '612',
                        'codigo_postal' => '54321',
                        'telefono' => '5557654321',
                        'email' => 'info@serviciosintegrales.com'
                    ]),
                    Proveedor::create([
                        'rfc' => 'ZAZZ030303000',
                        'razon_social' => 'Consultores Profesionales S.A.',
                        'nombre_comercial' => 'Consultores Pro',
                        'regimen_fiscal' => '601',
                        'codigo_postal' => '67890',
                        'telefono' => '5559876543',
                        'email' => 'contacto@consultores.com'
                    ])
                ]);
            }

            // Crear algunos tramites si no existen
            if ($tramites->isEmpty()) {
                $tramites = collect([
                    Tramite::create([
                        'tipo_tramite' => 'Inscripcion',
                        'estado' => 'En_Proceso',
                        'proveedor_id' => $proveedores->first()->id
                    ]),
                    Tramite::create([
                        'tipo_tramite' => 'Actualizacion',
                        'estado' => 'Pendiente',
                        'proveedor_id' => $proveedores->skip(1)->first()->id
                    ]),
                    Tramite::create([
                        'tipo_tramite' => 'Renovacion',
                        'estado' => 'En_Proceso',
                        'proveedor_id' => $proveedores->last()->id
                    ])
                ]);
            }
        }

        $tiposCita = ['Revision', 'Cotejo', 'Entrega'];
        $estados = ['Programada', 'Confirmada', 'Cancelada', 'Reagendada', 'Completada'];

        // Crear citas de ejemplo
        $citasData = [
            [
                'tramite_id' => $tramites->first()->id,
                'proveedor_id' => $proveedores->first()->id,
                'fecha_cita' => Carbon::now()->addDays(3)->setTime(10, 0),
                'tipo_cita' => 'Revision',
                'estado' => 'Programada',
                'atendido_por' => $usuarios->isNotEmpty() ? $usuarios->first()->id : null
            ],
            [
                'tramite_id' => $tramites->skip(1)->first()->id,
                'proveedor_id' => $proveedores->skip(1)->first()->id,
                'fecha_cita' => Carbon::now()->addDays(5)->setTime(14, 30),
                'tipo_cita' => 'Cotejo',
                'estado' => 'Confirmada',
                'atendido_por' => $usuarios->isNotEmpty() ? $usuarios->skip(1)->first()->id : null
            ],
            [
                'tramite_id' => $tramites->last()->id,
                'proveedor_id' => $proveedores->last()->id,
                'fecha_cita' => Carbon::now()->addDays(7)->setTime(9, 0),
                'tipo_cita' => 'Entrega',
                'estado' => 'Programada',
                'atendido_por' => null
            ],
            [
                'tramite_id' => $tramites->first()->id,
                'proveedor_id' => $proveedores->first()->id,
                'fecha_cita' => Carbon::now()->subDays(2)->setTime(11, 0),
                'tipo_cita' => 'Revision',
                'estado' => 'Completada',
                'atendido_por' => $usuarios->isNotEmpty() ? $usuarios->first()->id : null
            ],
            [
                'tramite_id' => $tramites->skip(1)->first()->id,
                'proveedor_id' => $proveedores->skip(1)->first()->id,
                'fecha_cita' => Carbon::now()->addDays(1)->setTime(16, 0),
                'tipo_cita' => 'Cotejo',
                'estado' => 'Reagendada',
                'atendido_por' => null
            ]
        ];

        foreach ($citasData as $citaData) {
            Cita::create($citaData);
        }

        // Crear algunas citas adicionales aleatorias
        for ($i = 0; $i < 10; $i++) {
            Cita::create([
                'tramite_id' => $tramites->random()->id,
                'proveedor_id' => $proveedores->random()->id,
                'fecha_cita' => Carbon::now()->addDays(rand(1, 30))->setTime(rand(8, 17), rand(0, 59)),
                'tipo_cita' => $tiposCita[array_rand($tiposCita)],
                'estado' => $estados[array_rand($estados)],
                'atendido_por' => $usuarios->isNotEmpty() && rand(0, 1) ? $usuarios->random()->id : null
            ]);
        }

        $this->command->info('Citas de ejemplo creadas exitosamente.');
    }
}