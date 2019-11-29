<?php

namespace Trax\Sync\Console;

use Illuminate\Console\Command;
use Trax\Sync\Pusher;

class Push extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trax:push {connector} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push the statements to an external repository';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $connector = $this->argument('connector');
        $all = $this->option('all');

        if ($all && !$this->confirm("This will reset the synchronization status with $connector. Do you wish to continue?")) {
            return;
        }

        $this->line("");
        $this->line("Pushing statements to {$connector}...");

        try {
            $start = time();
            $pusher = new Pusher();
            $feedback = $pusher->push($connector, $all);
            $time = time() - $start;
            $this->info($feedback . " ($time seconds)");

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->line("");
    }
}
