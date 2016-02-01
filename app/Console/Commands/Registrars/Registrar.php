<?php

namespace App\Console\Commands\Registrars;

use Illuminate\Console\Command;

interface Registrar
{
    /**
     * Constructor
     * @param Command $command Command to use for output.
     */
    public function __construct(Command $command);

    /**
     * Registers a new instance
     * @return void
     */
    public function register();

    /**
     * Update the env string with any new values
     * @param  string $envString environment string to update
     * @return string
     */
    public function updateEnv($envString);

    /**
     * Called after the user has finished registering new services.
     * @return void
     */
    public function afterRegistration();
}
