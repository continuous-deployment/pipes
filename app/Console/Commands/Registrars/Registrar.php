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
     */
    public function register();

    /**
     * Update the env string with any new values
     * @param  string $envString
     * @return string
     */
    public function updateEnv($envString);

    /**
     * Called after the user has finished registering new services.
     */
    public function afterRegistration();
}
