<?php

namespace App\Console\Commands;

use App\Services\IikoService;
use Illuminate\Console\Command;

class StoresUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stores:update';

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
        $this->info('Stores update start');
        $iikoService->updateStores();
        $this->info('Stores update done');
    }
}
