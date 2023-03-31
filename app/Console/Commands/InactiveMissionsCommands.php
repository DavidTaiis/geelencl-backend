<?php

namespace App\Console\Commands;

use App\Repositories\CampaignRepository;
use Illuminate\Console\Command;

class  InactiveMissionsCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inactive_missions';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle(CampaignRepository $campaignRepository)
    {
        $campaignRepository->inactiveMission();
    }
}
