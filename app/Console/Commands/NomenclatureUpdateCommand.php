<?php

namespace App\Console\Commands;

use App\Services\IikoService;
use Illuminate\Console\Command;

class NomenclatureUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nomenclature:update';

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
        $this->info('Nomenclature update start');
        $iikoService->updateProducts();
        $this->info('Nomenclature update done');
    }
}
