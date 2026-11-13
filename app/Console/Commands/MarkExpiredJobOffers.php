<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobOffer;
use Carbon\Carbon;

class MarkExpiredJobOffers extends Command
{
    protected $signature = 'jobs:mark-expired';
    protected $description = 'Marquer automatiquement les offres d\'emploi expirées';

    public function handle()
    {
        $expiredCount = JobOffer::where('status', 'active')
            ->whereNotNull('application_deadline')
            // La date limite est un jour entier : l'offre reste ouverte ce jour-là
            // et n'expire que le lendemain.
            ->whereDate('application_deadline', '<', Carbon::today())
            ->update(['status' => 'expired']);

        $this->info("Nombre d'offres marquées comme expirées : {$expiredCount}");
        
        return Command::SUCCESS;
    }
}
