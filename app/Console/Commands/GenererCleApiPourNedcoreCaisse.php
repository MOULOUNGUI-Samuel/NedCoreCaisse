<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenererCleApiPourNedcoreCaisse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generer-cle-api-pour-nedcore-caisse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = 'NedcoreCaisse_API_KEY';
        $path = base_path('.env');
        $newKeyValue = 'NedcoreCaisse_API_KEY=' . \Illuminate\Support\Str::random(40);

        if (File::exists($path)) {
            $fileContent = File::get($path);
            if (str_contains($fileContent, $key)) {
                // La clé existe, on la remplace
                File::put($path, preg_replace(
                    "/^$key=.*/m",
                    $newKeyValue,
                    $fileContent
                ));
                $this->info('La clé d\'API existante a été mise à jour.');
            } else {
                // La clé n'existe pas, on l'ajoute
                File::append($path, "\n" . $newKeyValue);
                $this->info('La nouvelle clé d\'API a été ajoutée au fichier .env.');
            }
        }

        // On nettoie le cache pour que le changement soit pris en compte
        $this->call('config:clear');

        $this->warn('La nouvelle clé API pour Nedcore caisse est : ' . explode('=', $newKeyValue)[1]);
        return Command::SUCCESS;
    }
    //php artisan app:generer-cle-api-pour-nedcore-caisse
}
