<?php

namespace App\Pipeline\Execution\Executors;

use App\Models\Action;
use App\Pipeline\Execution\Executor;

class SSHExecutor extends Executor
{
    /**
     * Type of executor
     * E.g. SSH, Docker
     *
     * @var string
     */
    protected $type = 'SSH';

    /**
     * Executes the action using the executor
     *
     * @param Action $action Action to execute
     *
     * @return array Array of data returned from commands
     */
    public function execute(Action $action)
    {
        if ($action->host === null) {
            return false;
        }

        $auth = $action->host->auth;

        if ($auth === null) {
            return false;
        }

        $commands = $action->commands;
        $session = $this->getSSHSession($auth);

        // Actually do the processing for SSH
        // dd($auth, $commands, $session);
        //
        return true;
    }

    /**
     * Gets the SSH session using the auth given
     *
     * @param \App\Models\Auth $auth Auth model
     */
    protected function getSSHSession($auth)
    {
        if ($auth->isKeyAuthentication()) {
            // create SSH key connection and return
        }

        // return default way (username and password)
    }
}
