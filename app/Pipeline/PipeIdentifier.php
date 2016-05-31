<?php

namespace Pipes\Pipeline;

use Pipes\Pipeline\Pipe;
use Illuminate\Contracts\Database\ModelIdentifier;

class PipeIdentifier
{
    /**
     * Class of the pipe
     *
     * @var string
     */
    protected $class;

    /**
     * Model identifier for the pipe
     *
     * @var \Illuminate\Contracts\Database\ModelIdentifier
     */
    protected $modelIdentifier;

    /**
     * Constructor
     *
     * @param Pipe $pipe Pipe to identify
     */
    public function __construct(Pipe $pipe)
    {
        $this->class = get_class($pipe);
        $model       = $pipe->getModel();

        $this->modelIdentifier = new ModelIdentifier(
            get_class($model),
            $model->getQueueableId()
        );
    }

    /**
     * Retrieves the currently set class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Retrieves the currently set modelIdentifier.
     *
     * @return \Illuminate\Contracts\Database\ModelIdentifier
     */
    public function getModelIdentifier()
    {
        return $this->modelIdentifier;
    }
}
