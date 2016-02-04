<?php

namespace App\Console\Commands;

use App\Console\Commands\Registrars\GitLabRegistrar;
use Illuminate\Console\Command;

class Register extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'register';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Items to register';

    /**
     * Collection of registrar implementations
     * @var \Illuminate\Support\Collection
     */
    protected $registrars;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->registrars = collect();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->registrars->put('GitLab', new GitLabRegistrar($this));
        $this->whatWouldYouLikeToRegister();
    }

    /**
     * Ask the user what they would like to register with the application
     *
     * @return void
     */
    public function whatWouldYouLikeToRegister()
    {
        $choices   = $this->registrars->keys();
        $choices[] = 'No more';

        $registerType = $this->askWithCompletion(
            'What would you like to register? (' .
                implode(', ', $choices->toArray()) .
            ')',
            $choices->toArray(),
            $choices->first()
        );

        if ($registerType === 'No more') {
            foreach ($this->registrars as $registrar) {
                $registrar->afterRegistration();
            }

            $this->updateEnvFile();

            return;
        }

        if ($registerType == '' || !$this->registrars->has($registerType)) {
            $this->error('Unable to register that. Please try again.');
            $this->whatWouldYouLikeToRegister();

            return;
        }

        $this->registrars->get($registerType)->register();
        $this->whatWouldYouLikeToRegister();
    }

    /**
     * Updates the env file.
     * @return void
     */
    protected function updateEnvFile()
    {
        $filename  = '.env';
        $envString = file_get_contents($filename);

        foreach ($this->registrars as $registrar) {
            $envString = $registrar->updateEnv($envString);
        }

        file_put_contents($filename, $envString);
    }
}
