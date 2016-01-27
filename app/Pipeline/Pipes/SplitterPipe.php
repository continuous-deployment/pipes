<?php

namespace App\Pipeline\Pipes;

use App\Models\Splitter;
use App\Pipeline\Pipe;
use App\Pipeline\PipeFactory;
use App\Pipeline\Traveler;

class SplitterPipe implements Pipe
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
     * @param Splitter $parameter Splitter to use when pipe gets called
     */
    public function __construct(Splitter $splitter)
    {
        $this->splitter = $splitter;
    }

    /**
     * Handles the incoming traveler and perform necessary action
     *
     * @param  Traveler $traveler The data sent from the previous pipe.
     * @return void
     */
    public function flowThrough(Traveler $traveler)
    {
        foreach ($this->splitter->splits as $split) {
            $pipeable = $split->pipeable;
            $pipe = PipeFactory::make($pipeable);

            if ($pipe === null) {
                continue;
            }

            $pipe->flowThrough($traveler);
        }
    }
}
