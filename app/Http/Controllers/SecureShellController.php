<?php

namespace App\Http\Controllers;

use Log;

class SecureShellController extends Controller
{
    /**
     * Handle secure shell connections.
     * @param Auth   $auth auth object for connection
     * @param Array  $commands commands to be run on remote host
     *
     * @return Array outputs from remote commands
     */
    public function executeCommands($auth, $commands)
    {
        $configuration = new Configuration($auth->host);
        $session = new Session($configuration);
        $authentication = new Password($auth->username, $auth->password);
        $authentication->authenticate($session->getResource());
        $exec = $session->getExec();
        $outputs = [];
        foreach ($commands as $command) {
            $outputs[] = $exec->run($command);
        }
        \Log::info($outputs);
        return $outputs;
    }
}
