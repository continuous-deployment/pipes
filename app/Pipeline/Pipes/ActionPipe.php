<?php

namespace App\Pipeline\Pipes;

use App\Models\Action;
use App\Pipeline\Pipe;
use App\Pipeline\PipeFactory;
use App\Pipeline\Traveler;

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
        // TODO: Do actual processing.
        \Log::info('PROCESSING ACTION: ' . $this->action->action);

        return true && $traveler;
    }
}
