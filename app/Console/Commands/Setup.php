<?php

namespace Pipes\Console\Commands;

use Illuminate\Console\Command;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Sets up pipes to use';

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        $refresh = $this->confirm(
            'Would you like to refresh the database?',
            false
        );
        if ($refresh) {
            $this->call('migrate:refresh');
        } else {
            $this->call('migrate');
        }

        $this->call('register');
    }
}
