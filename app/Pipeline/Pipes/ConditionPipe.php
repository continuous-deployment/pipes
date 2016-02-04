<?php

namespace App\Pipeline\Pipes;

use App\Models\Condition;
use App\Pipeline\Pipe;
use App\Pipeline\Traveler\Bag;

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
     * @param Bag $bag The data sent from the previous pipe.
     *
     * @return \Illuminate\Database\Eloquent\Model|array
     */
    public function flowThrough(Bag $bag)
    {
        if ($this->runCondition($bag)) {
            return $this->condition->success_pipeable;
        }

        return $this->condition->failure_pipeable;
    }

    /**
     * Run the condition using the values from the condition model
     *
     * @param Bag $bag Traveler bag object with data from the pipeline
     *
     * @return boolean
     */
    protected function runCondition(Bag $bag)
    {
        $fieldValue = $bag->lookAt($this->condition->field);
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
