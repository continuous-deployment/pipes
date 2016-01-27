<?php

namespace App\Pipeline\Pipes;

use App\Models\Condition;
use App\Pipeline\Pipe;
use App\Pipeline\PipeFactory;
use App\Pipeline\Traveler;

class ConditionPipe implements Pipe
{
    /**
     * Condition model
     *
     * @var \App\Models\Condition
     */
    protected $condition;

    /**
     * Constructor
     *
     * @param Condition $parameter Condition to use when pipe gets called
     */
    public function __construct(Condition $condition)
    {
        $this->condition = $condition;
    }

    /**
     * Handles the incoming traveler and perform necessary action
     *
     * @param  Traveler $traveler The data sent from the previous pipe.
     * @return void
     */
    public function flowThrough(Traveler $traveler)
    {
        $pipe = null;

        if ($this->runCondition($traveler)) {
            $pipe = PipeFactory::make($this->condition->success_pipeable);
        } else {
            $pipe = PipeFactory::make($this->condition->failure_pipeable);
        }

        if ($pipe === null) {
            return;
        }

        $pipe->flowThrough($traveler);
    }

    /**
     * Run the condition using the values from the condition model
     *
     * @param Traveler $traveler Traveler object with data from the pipeline
     *
     * @return boolean
     */
    protected function runCondition(Traveler $traveler)
    {
        $fieldValue = $traveler->lookAt($this->condition->field);

        switch ($this->condition->operator) {
            case '==':
            default:
                return $fieldValue == $this->condition->value;
            case '===':
                return $fieldValue === $this->condition->value;
            case '!=':
                return $fieldValue != $this->condition->value;
            case '!==':
                return $fieldValue !== $this->condition->value;
            case '>':
                return $fieldValue > $this->condition->value;
            case '>=':
                return $fieldValue >= $this->condition->value;
            case '<':
                return $fieldValue < $this->condition->value;
            case '<=':
                return $fieldValue <= $this->condition->value;
            case '<>':
                return $fieldValue <> $this->condition->value;
        }
    }
}
