<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class UpdateMediaMimeTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:update-mime-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour les types MIME manquants pour tous les médias';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des médias sans type MIME...');
        
        $medias = Media::whereNull('mime_type')
            ->orWhere('mime_type', '')
            ->get();
        
        if ($medias->isEmpty()) {
            $this->info('Aucun média à mettre à jour.');
            return 0;
        }
        
        $this->info("Trouvé {$medias->count()} média(s) à mettre à jour.");
        
        $bar = $this->output->createProgressBar($medias->count());
        $bar->start();
        
        $updated = 0;
        $failed = 0;
        
        foreach ($medias as $media) {
            try {
                if ($media->chemin && Storage::disk('public')->exists($media->chemin)) {
                    $fullPath = storage_path('app/public/' . $media->chemin);
                    
                    if (file_exists($fullPath)) {
                        $mimeType = mime_content_type($fullPath);
                        
                        if ($mimeType) {
                            $media->mime_type = $mimeType;
                            
                            // Mettre à jour aussi la taille si elle est vide
                            if (empty($media->taille)) {
                                $media->taille = filesize($fullPath);
                            }
                            
                            $media->save();
                            $updated++;
                        } else {
                            $failed++;
                        }
                    } else {
                        $failed++;
                    }
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Erreur pour le média ID {$media->id_media}: " . $e->getMessage());
                $failed++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Mise à jour terminée : {$updated} média(s) mis à jour, {$failed} échec(s).");
        
        return 0;
    }
}
