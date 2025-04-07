<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;

class PurgeOldContacts extends Command
{
    protected $signature = 'contacts:purge';
    protected $description = 'Purge les contacts soft-deleted depuis plus de 45 jours';

    public function handle()
    {
        $days = 45;
        Contact::onlyTrashed()->where('deleted_at', '<=', now()->subDays($days)->endOfDay())->forceDelete();
        $this->info("Contacts supprimés depuis plus de {$days} jours ont été purgés.");
    }
}
