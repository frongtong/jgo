<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Webpanel\InnovationController;
use App\Http\Controllers\Webpanel\NewsnewController;
use Illuminate\Http\Request;

class HandleInnovationAndNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handle:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run handle methods in InnovationController and NewsnewController';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $request = new Request();

        // เรียกใช้งาน Controller โดยตรง
        app(InnovationController::class)->handle($request);
        app(NewsnewController::class)->handle($request);

        $this->info('Innovation & News handle executed successfully!');
    }
}
