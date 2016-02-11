<?php

namespace App\Pipeline\Pipes;

use App\Models\Action;
use App\Pipeline\Pipe;
use App\Pipeline\Traveler\Bag;

class ActionPipe extends Pipe
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
     * @param Bag $bag The data sent from the previous pipe.
     *
     * @return \Illuminate\Database\Eloquent\Model|array
     */
    public function flowThrough(Bag $bag)
    {
        $result = $this->processAction($bag);

        if ($result) {
            \Log::info('SUCCESSLY PROCESSED ACTION');
        } else {
            \Log::info('UNSUCCESSLY PROCESSED ACTION');
        }

        $pipeable = $this->action->pipeable;

        return $pipeable;
    }

    /**
     * Processes the action
     *
     * @param Bag $bag Travelers Bag object
     *
     * @return boolean
     */
    public function processAction(Bag $bag)
    {
        $commands = $this->action->commands;

        foreach ($commands as $command) {
            // TODO: Do actual processing.
            \Log::info('PROCESSING ACTION: ' . $command->command);
        }

        return true && $bag;
    }

    /**
     * Gets the model related to this pipe
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->action;
    }
}
