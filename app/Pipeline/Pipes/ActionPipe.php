<?php

namespace App\Pipeline\Pipes;

use App\Models\Action;
use App\Pipeline\Pipe;
use App\Pipeline\PipeFactory;
use App\Pipeline\Traveler;
use App\Http\Controllers\SecureShellController;

class ActionPipe implements Pipe
{
    /**
     * Action model
     *
     * @var \App\Models\Action
     */
    protected $action;

    /**
     * Constructor
     *
     * @param Action $action Action to use when pipe gets called
     */
    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    /**
     * Handles the incoming traveler and perform necessary action
     *
     * @param  Traveler $traveler The data sent from the previous pipe.
     * @return void
     */
    public function flowThrough(Traveler $traveler)
    {
        $result = $this->processAction($traveler);

        if ($result) {
            \Log::info('SUCCESSLY PROCESSED ACTION');
        } else {
            \Log::info('UNSUCCESSLY PROCESSED ACTION');
        }

        $pipeable = $this->action->pipeable;
        $pipe     = PipeFactory::make($pipeable);
        if ($pipe === null) {
            return;
        }

        $pipe->flowThrough($traveler);
    }

    /**
     * Processes the action
     *
     * @param  Traveler $traveler Traveler object
     * @return boolean
     */
    public function processAction(Traveler $traveler)
    {
        $commands = $this->action->commands;

        foreach ($commands as $command) {
            // TODO: Do actual processing.
            \Log::info('PROCESSING ACTION: ' . $command->command);
            $prefix = 'ssh ';
            if (substr($command->command, 0, strlen($prefix)) == $prefix) {
                $sshCommands = [];
                $sshCommands[] = substr($command->command, strlen($prefix));

                $auth = new stdClass;
                $auth->host = env('SSH_HOST');
                $auth->username = env('SSH_USER');
                $auth->password = env('SSH_PASSWORD');
                SecureShellController::executeCommands($auth, $sshCommands);
            }
        }

        return true && $traveler;
    }
}
