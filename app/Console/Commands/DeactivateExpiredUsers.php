<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeactivateExpiredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desactiva usuarios cuya fecha de expiración ha pasado';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        
        $expiredUsers = User::where('active', true)
            ->whereNotNull('active_until')
            ->where('active_until', '<', $now)
            ->get();
        
        $count = $expiredUsers->count();
        
        if ($count > 0) {
            foreach ($expiredUsers as $user) {
                $this->info("Desactivando usuario: {$user->name} ({$user->email})");
                $user->active = false;
                $user->save();
            }
            
            $this->info("Se han desactivado {$count} usuarios expirados.");
        } else {
            $this->info("No hay usuarios expirados para desactivar.");
        }
        
        return 0;
    }
}
