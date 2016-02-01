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
     * @param Condition $condition Condition to use when pipe gets called
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
        $operator   = $this->condition->operator;

        if ($this->checkEqualsAndNotEquals($operator, $fieldValue)) {
            return true;
        }

        if ($this->checkGreaterAndLessThan($operator, $fieldValue)) {
            return true;
        }

        return false;
    }

    /**
     * Checks the ==, ===, !=, !== operators
     *
     * @param string $operator   Operator to check
     * @param mixed  $fieldValue Value to check against
     *
     * @return boolean
     */
    protected function checkEqualsAndNotEquals($operator, $fieldValue)
    {
        if ($operator === '==') {
            return $fieldValue == $this->condition->value;
        }

        if ($operator === '===') {
            return $fieldValue === $this->condition->value;
        }

        if ($operator === '!=') {
            return $fieldValue != $this->condition->value;
        }

        if ($operator === '!==') {
            return $fieldValue !== $this->condition->value;
        }

        return false;
    }

    /**
     * Checks the >, >=, <, <= operators
     *
     * @param string $operator   Operator to check
     * @param mixed  $fieldValue Value to check against
     *
     * @return boolean
     */
    protected function checkGreaterAndLessThan($operator, $fieldValue)
    {
        if ($operator === '>') {
            return $fieldValue > $this->condition->value;
        }

        if ($operator === '>=') {
            return $fieldValue >= $this->condition->value;
        }

        if ($operator === '<') {
            return $fieldValue < $this->condition->value;
        }

        if ($operator === '<=') {
            return $fieldValue <= $this->condition->value;
        }
    }
}
