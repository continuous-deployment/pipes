<?php

namespace App\Pipeline;

use Illuminate\Database\Eloquent\Model;

class Pipeline
{
    /**
     * Object that will get passed to each pipe
     *
     * @var \App\Pipeline\Traveler
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
     * Starts the pipeline with pipe given
     *
     * @param  Pipe $initialPipe Initial Pipe object to process
     * @return void
     */
    public function startWith(Pipe $initialPipe)
    {
        $initialPipe->flowThrough($this->traveler);
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
