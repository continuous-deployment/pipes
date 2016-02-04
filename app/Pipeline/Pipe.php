<?php

namespace App\Pipeline;

use App\Pipeline\Traveler\Bag;

interface Pipe
{
    /**
     * Handles the incoming traveler and perform necessary action
     *
     * @param Bag $bag The data sent from the previous pipe.
     *
     * @return \Illuminate\Database\Eloquent\Model|array
     */
    public function flowThrough(Bag $bag);
}
