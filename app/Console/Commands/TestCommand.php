<?php

namespace App\Console\Commands;

use App\Processes\EventProcess;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param EventProcess $eventProcess
     */
    public function handle(EventProcess $eventProcess)
    {
        $eventId = $this->ask("Identificador del evento a cancelar");
        $eventProcess->recalculateRanking($eventId);
        $this->info("Termino de recalcular");
    }
}
