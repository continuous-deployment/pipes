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
            \Log::info('SUCCESSFULLY PROCESSED ACTION');
        } else {
            \Log::info('UNSUCCESSFULLY PROCESSED ACTION');
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
        $type = $this->action->type;

        /** @var \App\Pipeline\Execution\Manager $executorManager */
        $executorManager = app('ExecutorManager');
        $executor = $executorManager->getByType($type);

        if ($executor === null) {
            return false;
        }

        $output = $executor->execute($this->action);
        if ($output === false) {
            return false;
        }

        $bag->give($output, 'ExecutorOutput');

        return true;
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
