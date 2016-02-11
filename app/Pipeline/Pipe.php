<?php

namespace App\Pipeline;

use App\Models\Stream;
use App\Pipeline\Traveler\Bag;

abstract class Pipe
{
    /**
     * Current stream the pipe is on
     *
     * @var \App\Models\Stream
     */
    protected $stream;

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

    /**
     * Sets the stream this pipe is on
     *
     * @param  Stream $stream
     * @return self
     */
    public function setStream(Stream $stream)
    {
        $this->stream = $stream;

        return $this;
    }
}
