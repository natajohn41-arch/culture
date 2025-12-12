<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\Exports\AllContentsSeeder;

class ImportAllContents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contents:import-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importe tous les contenus locaux dans la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Import des contenus en cours...');
        
        try {
            $seeder = new AllContentsSeeder();
            $seeder->setCommand($this);
            $seeder->run();
            
            $this->info('✅ Import terminé avec succès !');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'import: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

