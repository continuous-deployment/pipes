<?php

namespace Pipes\Pipeline\Execution\Executors;

use Pipes\Models\Action;
use Pipes\Pipeline\Execution\Executor;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;
use Illuminate\Filesystem\Filesystem as File;

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

        $session = $this->getSSHSession($auth, $action->host->host);
        $commandOutputs = [];
        foreach ($commands as $command) {
            $commandOutputs[] = $session->exec($command->command);
        }
        foreach ($commandOutputs as $output) {
            \Log::info($output);
        }

        return true;
    }

    /**
     * Gets the SSH session using the auth given
     *
     * @param \Pipes\Models\Auth $auth Auth model
     * @param \Ssh\Configuration $configuration SSH configuration object
     */
    protected function getSSHSession($auth, $host)
    {
        $session = new SSH2($host);
        if ($auth->isKeyAuthentication()) {
            $key = new RSA();
            $key->loadKey($auth->credentials->key);
            if (!$session ->login($auth->credentials->username, $key)) {
                \log::error('Login Failed');
            }
        } else {
            if (!$session->login(
                $auth->credentials->username,
                $auth->credentials->password
            )) {
                \log::error('Login Failed');
            }
        }
        return $session;
    }
}
