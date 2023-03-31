<?php

namespace App\Console\Commands;

use App\Repositories\OrderRepository;
use Illuminate\Console\Command;

class CalculateQualificationProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate_qualification_provider';

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
    public function handle()
    {
        $orderRepository =  new OrderRepository();
        $orderRepository->calculateQualification(2);
    }
}
