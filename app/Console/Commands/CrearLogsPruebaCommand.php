<?php

namespace App\Console\Commands;

use App\Models\Log;
use App\Models\User;
use Illuminate\Console\Command;

class CrearLogsPruebaCommand extends Command
{
    protected $signature = 'logs:crear-prueba {cantidad=10}';
    protected $description = 'Crear logs de prueba para el sistema';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');
        $users = User::all();
        
        $levels = ['info', 'warning', 'error', 'debug'];
        $channels = ['auth', 'security', 'files', 'database', 'system', 'api'];
        $messages = [
            'Usuario ha iniciado sesión correctamente',
            'Usuario ha cerrado sesión',
            'Intento de acceso fallido a recurso protegido',
            'Error al procesar archivo: archivo no encontrado',
            'Consulta de base de datos ejecutada',
            'Archivo subido exitosamente',
            'Error de validación en formulario',
            'Solicitud API procesada correctamente',
            'Error de conexión a base de datos',
            'Backup del sistema completado',
            'Usuario creado exitosamente',
            'Error al enviar email',
            'Sesión expirada',
            'Acceso denegado por permisos insuficientes',
            'Configuración del sistema actualizada'
        ];
        
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        $ips = ['192.168.1.100', '192.168.1.101', '192.168.1.102', '127.0.0.1', '10.0.0.1'];
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            'Laravel/10.0',
            'PostmanRuntime/7.32.3'
        ];
        $urls = [
            'http://localhost/login',
            'http://localhost/admin',
            'http://localhost/archivos/upload',
            'http://localhost/api/users',
            'http://localhost/dashboard',
            'http://localhost/tramites',
            'http://localhost/revisiones',
            'http://localhost/notificaciones'
        ];

        $bar = $this->output->createProgressBar($cantidad);
        $bar->start();

        for ($i = 0; $i < $cantidad; $i++) {
            $level = $levels[array_rand($levels)];
            $channel = $channels[array_rand($channels)];
            $message = $messages[array_rand($messages)];
            $method = $methods[array_rand($methods)];
            $ip = $ips[array_rand($ips)];
            $userAgent = $userAgents[array_rand($userAgents)];
            $url = $urls[array_rand($urls)];
            
            // 70% de probabilidad de que tenga usuario asociado
            $userId = rand(1, 10) <= 7 && $users->count() > 0 ? $users->random()->id : null;
            
            $context = [];
            if ($level === 'error') {
                $context = ['error_code' => rand(1000, 9999), 'stack_trace' => 'Error trace...'];
            } elseif ($level === 'info') {
                $context = ['action' => 'user_action', 'timestamp' => now()->toISOString()];
            } elseif ($level === 'warning') {
                $context = ['warning_type' => 'security', 'severity' => 'medium'];
            }

            Log::create([
                'level' => $level,
                'message' => $message,
                'channel' => $channel,
                'user_id' => $userId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'url' => $url,
                'method' => $method,
                'context' => !empty($context) ? json_encode($context) : null,
                'created_at' => now()->subMinutes(rand(1, 1440)), // Últimas 24 horas
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Se han creado {$cantidad} logs de prueba exitosamente.");
        
        return 0;
    }
}
