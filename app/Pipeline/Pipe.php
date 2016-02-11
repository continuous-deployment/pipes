<?php

namespace App\Pipeline;

use App\Pipeline\Traveler\Bag;

abstract class Pipe
{
    /**
     * Handles the incoming traveler and perform necessary action
     *
     * @param Bag $bag The data sent from the previous pipe.
     *
     * @return \Illuminate\Database\Eloquent\Model|array
     */
    abstract public function flowThrough(Bag $bag);

    /**
     * Gets the model related to this pipe
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function getModel();
}
