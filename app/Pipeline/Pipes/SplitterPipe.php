<?php

namespace App\Pipeline\Pipes;

use App\Models\Splitter;
use App\Pipeline\Pipe;
use App\Pipeline\Traveler\Bag;
use App\Pipeline\Traveler\Traveler;

class SplitterPipe extends Pipe
{
    /**
     * Splitter model
     *
     * @var \App\Models\Splitter
     */
    protected $splitter;

    /**
     * Constructor
     *
     * @param Splitter $splitter Splitter to use when pipe gets called
     */
    public function __construct(Splitter $splitter)
    {
        $this->splitter = $splitter;
    }

    /**
     * Handles the incoming traveler and perform necessary action
     *
     * @param Bag $bag The data sent from the previous pipe.
     *
     * @return \Illuminate\Database\Eloquent\Model|array
     * @SuppressWarnings("unused")
     */
    public function flowThrough(Bag $bag)
    {
        $pipeables = [];

        foreach ($this->splitter->splits as $split) {
            $pipeables[] = $split->pipeable;
        }

        return $pipeables;
    }

    /**
     * Gets the model related to this pipe
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->splitter;
    }
}
