<?php

namespace App\Pipeline;

use App\Models\Stream;
use App\Pipeline\Traveler\Traveler;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Pipeline
{
    /**
     * Object that will get passed to each pipe
     *
     * @var \App\Pipeline\Traveler\Traveler
     */
    protected $traveler;

    /**
     * Set the traveler object being sent on the pipeline.
     *
     * @param  Traveler $traveler Traveler to be passed to the pipes
     * @return $this
     */
    public function send(Traveler $traveler)
    {
        $this->traveler = $traveler;

        return $this;
    }

    /**
     * Start the pipeline using the stream
     *
     * @param  Stream $stream Stream to start pipeline
     * @return void
     */
    public function flow(Stream $stream)
    {
        $pipeable = $stream->pipeable;

        if ($pipeable === null) {
            throw new InvalidArgumentException(
                'Stream does not having starting pipeable'
            );
        }

        $this->startWithModel($pipeable);
    }

    /**
     * Starts the pipeline with pipe given
     *
     * @param  Pipe $initialPipe Initial Pipe object to process
     * @return void
     */
    public function startWith(Pipe $initialPipe)
    {
        if ($this->traveler === null) {
            $this->traveler = new Traveler();
        }

        $this->traveler->travel($initialPipe);
    }

    /**
     * Starts the pipeline with a model
     *
     * @param  Model $model Model that will be used to get the correct pipe
     * @return void
     */
    public function startWithModel(Model $model)
    {
        $initialPipe = PipeFactory::make($model);
        $this->startWith($initialPipe);
    }
}
