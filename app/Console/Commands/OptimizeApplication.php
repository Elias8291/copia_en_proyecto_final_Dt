<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class OptimizeApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize {--force : Force optimization without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the application for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('¿Desea optimizar la aplicación? Esto puede tomar varios minutos.')) {
            $this->info('Optimización cancelada.');
            return;
        }

        $this->info('🚀 Iniciando optimización de la aplicación...');

        // 1. Limpiar cache
        $this->info('📦 Limpiando cache...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        // 2. Optimizar configuración
        $this->info('⚙️ Optimizando configuración...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // 3. Optimizar autoloader
        $this->info('🔄 Optimizando autoloader...');
        $this->execCommand('composer dump-autoload --optimize');

        // 4. Verificar permisos de storage
        $this->info('📁 Verificando permisos de storage...');
        $storagePath = storage_path();
        $bootstrapCachePath = bootstrap_path('cache');

        if (!File::isWritable($storagePath)) {
            $this->warn('⚠️ El directorio storage no tiene permisos de escritura');
        }

        if (!File::isWritable($bootstrapCachePath)) {
            $this->warn('⚠️ El directorio bootstrap/cache no tiene permisos de escritura');
        }

        // 5. Optimizar base de datos
        $this->info('🗄️ Optimizando base de datos...');
        try {
            Artisan::call('migrate:status');
            $this->info('✅ Base de datos verificada');
        } catch (\Exception $e) {
            $this->error('❌ Error al verificar base de datos: ' . $e->getMessage());
        }

        // 6. Verificar archivos de configuración
        $this->info('📋 Verificando archivos de configuración...');
        $this->checkConfigurationFiles();

        $this->info('✅ Optimización completada exitosamente!');
        $this->info('🎯 La aplicación debería funcionar más rápido ahora.');
        
        $this->newLine();
        $this->info('💡 Recomendaciones adicionales:');
        $this->line('   • Configure un servidor web optimizado (nginx/apache)');
        $this->line('   • Use Redis para cache si es posible');
        $this->line('   • Configure CDN para archivos estáticos');
        $this->line('   • Monitoree el rendimiento con herramientas como Laravel Telescope');
    }

    private function execCommand($command)
    {
        $output = [];
        $returnVar = 0;
        
        exec($command . ' 2>&1', $output, $returnVar);
        
        if ($returnVar !== 0) {
            $this->warn('⚠️ Comando ejecutado con advertencias: ' . implode("\n", $output));
        } else {
            $this->info('✅ Comando ejecutado exitosamente');
        }
    }

    private function checkConfigurationFiles()
    {
        $configFiles = [
            'app.php',
            'database.php',
            'filesystems.php',
            'cache.php'
        ];

        foreach ($configFiles as $file) {
            $path = config_path($file);
            if (File::exists($path)) {
                $this->info("   ✅ {$file} - OK");
            } else {
                $this->warn("   ⚠️ {$file} - No encontrado");
            }
        }
    }
}
