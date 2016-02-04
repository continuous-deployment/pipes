<?php

namespace App\Pipeline\Traveler;

use App\Pipeline\Pipe;
use App\Pipeline\PipeFactory;
use Illuminate\Database\Eloquent\Model;

class Traveler
{
    /**
     * Bag that holds all the travelers items
     *
     * @var \App\Pipeline\Traveler\Bag
     */
    public $bag;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bag = new Bag();
    }

    /**
     * Sends the traveler down a pipe
     *
     * @param Pipe $pipe Pipe to send traveler down
     *
     * @return void
     */
    public function travel(Pipe $pipe)
    {
        $models = $pipe->flowThrough($this->bag);

        $pipes = $this->getPipes($models);

        if (count($pipes) === 0) {
            // END OF THE LINE
            \Log::info('PIPELINE FINISHED');

            return;
        }

        foreach ($pipes as $pipe) {
            $this->travel($pipe);
        }
    }

    /**
     * Gets the pipe object from the model
     *
     * @param Model $pipeable Model to get pipe from
     *
     * @return \App\Pipeline\Pipe|null
     */
    public function getPipeFromPipeable(Model $pipeable)
    {
        $pipe = PipeFactory::make($pipeable);
        if ($pipe === null) {
            return;
        }

        return $pipe;
    }

    /**
     * Gets all the pipes from the given models
     *
     * @param \Illuminate\Database\Eloquent\Model|array $models Array of models
     *
     * @return \App\Pipeline\Pipe[]
     */
    public function getPipes($models)
    {
        if (!is_array($models)) {
            $models = [$models];
        }

        $pipes = [];

        foreach ($models as $model) {
            if ($model === null) {
                continue;
            }

            $pipe = $this->getPipeFromPipeable($model);

            if ($pipe === null) {
                continue;
            }

            $pipes[] = $pipe;
        }

        return $pipes;
    }
}
