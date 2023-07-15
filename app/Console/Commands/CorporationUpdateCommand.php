<?php

namespace App\Console\Commands;

use App\Services\IikoService;
use Illuminate\Console\Command;

class CorporationUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'corporations:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(IikoService $iikoService): void
    {
        $this->info('Corporations update start');
        $iikoService->updateCorporations();
        $this->info('Corporations update done');
    }
}
