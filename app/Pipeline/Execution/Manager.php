<?php

namespace App\Pipeline\Execution;

class Manager
{
    /**
     * All the executors that are available
     *
     * @var \App\Pipeline\Executors\Executor[]
     */
    protected $executors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->executors = [];
    }

    /**
     * Register a new executor to the manager
     *
     * @param  Executor $executor Executor to register
     *
     * @return void
     */
    public function register(Executor $executor)
    {
        $type = $executor->getType();
        $type = strtolower($type);

        $this->executors[$type] = $executor;
    }

    /**
     * Gets the executor by the type given
     *
     * @param  string $type Type to search for
     *
     * @return \App\Pipeline\Execution\Executor|null
     */
    public function getByType($type)
    {
        $type = strtolower($type);

        if (array_key_exists($type, $this->executors)) {
            return $this->executors[$type];
        }

        return null;
    }
}
