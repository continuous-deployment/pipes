<?php

namespace App\Pipeline\Pipes;

use App\Models\Action;
use App\Pipeline\Pipe;
use App\Pipeline\PipeFactory;
use App\Pipeline\Traveler;
use Ssh\Authentication\Password;
use Ssh\Configuration;
use Ssh\Session;

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
                $sshCommand = substr($command->command, strlen($prefix));
                $configuration = new Configuration(env('SSH_HOST'));
                $session = new Session($configuration);
                $authentication = new Password(env('SSH_USER'), env('SSH_PASSWORD'));
                $login = $authentication->authenticate($session->getResource());
                $exec = $session->getExec();
                \Log::info($exec->run($sshCommand));
            }
        }

        return true && $traveler;
    }
}
