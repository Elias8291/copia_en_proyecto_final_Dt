<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckVerificationTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:verification-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar el estado de los tokens de verificación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE TOKENS ===');
        
        // Usuarios con tokens
        $usersWithTokens = User::whereNotNull('verification_token')->get();
        
        if ($usersWithTokens->isEmpty()) {
            $this->error('❌ No se encontraron usuarios con tokens de verificación.');
        } else {
            $this->info('✅ Usuarios con tokens de verificación:');
            foreach ($usersWithTokens as $user) {
                $this->line("ID: {$user->id} | Email: {$user->correo} | RFC: {$user->rfc}");
                $this->line("Verificado: " . ($user->verification ? 'SÍ' : 'NO'));
                $this->line("Token: " . substr($user->verification_token, 0, 20) . "...");
                $this->line("Creado: {$user->created_at}");
                $this->line("---");
            }
        }
        
        // Estadísticas
        $verified = User::where('verification', true)->count();
        $unverified = User::where('verification', false)->count();
        
        $this->info("\n📊 Estadísticas:");
        $this->line("👥 Usuarios verificados: {$verified}");
        $this->line("⏳ Usuarios no verificados: {$unverified}");
        
        return Command::SUCCESS;
    }
}
