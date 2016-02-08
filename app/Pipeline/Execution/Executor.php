<?php

namespace App\Pipeline\Execution;

use App\Models\Action;

abstract class Executor
{
    /**
     * Type of executor
     * E.g. SSH, Docker
     *
     * @var string
     */
    protected $type;

    /**
     * Executes the action using the executor
     *
     * @param  Action $action Action to execute
     *
     * @return array Array of data returned from commands
     */
    abstract public function execute(Action $action);

    /**
     * Gets the type of the executor
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
