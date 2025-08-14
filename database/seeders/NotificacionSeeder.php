<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notificacion;
use App\Models\User;
use Carbon\Carbon;

class NotificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los primeros usuarios registrados para asignar notificaciones
        $usuarios = User::limit(5)->get();
        
        if ($usuarios->isEmpty()) {
            $this->command->info('No hay usuarios registrados. Creando usuario de prueba...');
            $usuario = User::create([
                'nombre' => 'Usuario Prueba',
                'correo' => 'usuario.prueba@oaxaca.gob.mx',
                'rfc' => 'USUP850101OAX123',
                'password' => bcrypt('password'),
                'confirmacion' => true,
                'ultimo_acceso' => now()
            ]);
            $usuarios = collect([$usuario]);
        }

        $tiposNotificaciones = [
            [
                'tipo' => 'informativo',
                'titulo' => 'Bienvenido al sistema',
                'mensaje' => 'Te damos la bienvenida a nuestro sistema de gestión de trámites. Aquí podrás gestionar todos tus trámites de manera eficiente.',
                'leida' => true
            ],
            [
                'tipo' => 'Tramite',
                'titulo' => 'Trámite aprobado',
                'mensaje' => 'Tu trámite con folio #TR-2024-001 ha sido aprobado exitosamente. Puedes descargar tu documento desde el panel de trámites.',
                'leida' => false,
                'accion_url' => '/tramites'
            ],
            [
                'tipo' => 'advertencia',
                'titulo' => 'Documentos faltantes',
                'mensaje' => 'Tu trámite requiere documentación adicional. Por favor, revisa los documentos solicitados y súbelos a la brevedad.',
                'leida' => false,
                'accion_url' => '/tramites'
            ],
            [
                'tipo' => 'Cita',
                'titulo' => 'Cita programada',
                'mensaje' => 'Se ha programado una cita para el día 15 de agosto a las 10:00 AM. Por favor, asiste puntualmente con la documentación requerida.',
                'leida' => false,
                'accion_url' => '/citas'
            ],
            [
                'tipo' => 'error',
                'titulo' => 'Error en el procesamiento',
                'mensaje' => 'Se ha detectado un error en el procesamiento de tu trámite. Nuestro equipo técnico está trabajando para resolverlo.',
                'leida' => false
            ],
            [
                'tipo' => 'exito',
                'titulo' => 'Pago confirmado',
                'mensaje' => 'Tu pago ha sido procesado exitosamente. El trámite continuará con su proceso normal.',
                'leida' => true
            ],
            [
                'tipo' => 'informativo',
                'titulo' => 'Mantenimiento programado',
                'mensaje' => 'El sistema tendrá mantenimiento el próximo domingo de 2:00 AM a 6:00 AM. Durante este tiempo el servicio no estará disponible.',
                'leida' => false
            ],
            [
                'tipo' => 'Tramite',
                'titulo' => 'Documentos rechazados',
                'mensaje' => 'Algunos documentos de tu trámite han sido rechazados. Revisa las observaciones y vuelve a subirlos corregidos.',
                'leida' => false,
                'accion_url' => '/tramites'
            ],
            [
                'tipo' => 'Cita',
                'titulo' => 'Recordatorio de cita',
                'mensaje' => 'Te recordamos que tienes una cita mañana a las 10:00 AM. No olvides traer toda la documentación requerida.',
                'leida' => false,
                'accion_url' => '/citas'
            ],
            [
                'tipo' => 'exito',
                'titulo' => 'Perfil actualizado',
                'mensaje' => 'Tu información de perfil ha sido actualizada correctamente. Los cambios ya están en vigor.',
                'leida' => true
            ]
        ];

        foreach ($usuarios as $usuario) {
            foreach ($tiposNotificaciones as $index => $notificacion) {
                Notificacion::create([
                    'usuario_id' => $usuario->id,
                    'tipo' => $notificacion['tipo'],
                    'titulo' => $notificacion['titulo'],
                    'mensaje' => $notificacion['mensaje'],
                    'leida' => $notificacion['leida'],
                    'accion_url' => $notificacion['accion_url'] ?? null,
                    'fecha_lectura' => $notificacion['leida'] ? Carbon::now()->subDays(rand(1, 7)) : null,
                    'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                    'datos_adicionales' => [
                        'origen' => 'seeder',
                        'prioridad' => rand(1, 5),
                        'categoria' => $notificacion['tipo']
                    ]
                ]);
            }
        }

        $this->command->info('Se han creado ' . ($usuarios->count() * count($tiposNotificaciones)) . ' notificaciones de ejemplo.');
    }
}
