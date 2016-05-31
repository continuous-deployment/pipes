<?php

namespace Pipes\Pipeline;

use Pipes\Pipeline\PipeIdentifier;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class PipeFactory
{
    /**
     * Makes the correct pipe depending on the value given
     *
     * @param mixed $identifier This can be a Model or a class string
     *
     * @return \Pipes\Pipeline\Pipe|null
     */
    public static function make($identifier)
    {
        if (is_string($identifier) && class_exists($identifier)) {
            $mysteriousPipe = app($identifier);
            if ($mysteriousPipe instanceof Pipe) {
                return $mysteriousPipe;
            }
        }

        if ($identifier instanceof Model) {
            return self::makeFromModel($identifier);
        }

        return null;
    }

    /**
     * Makes pipe from the pipe identifier given
     *
     * @param  PipeIdentifier $identifier Identifier to make from
     *
     * @return \Pipes\Pipeline\Pipe|null
     */
    public static function makeFromPipeIdentifier(PipeIdentifier $identifier)
    {
        $class = $identifier->getClass();

        if (class_exists($class)) {
            $modelIdentifier = $identifier->getModelIdentifier();

            $model = (new $modelIdentifier->class)
                ->findOrFail($modelIdentifier->id);

            $mysteriousPipe = new $class($model);

            return $mysteriousPipe;
        }

        return null;
    }

    /**
     * Make a pipe from a model
     *
     * @param Model $model Model to be given to a pipe
     *
     * @return \Pipes\Pipeline\Pipe|null
     */
    protected static function makeFromModel(Model $model)
    {
        $reflectionModel = new ReflectionClass($model);
        $className       = $reflectionModel->getShortName();
        $pipeClass       = '\Pipes\Pipeline\Pipes\\' . $className . 'Pipe';

        if (class_exists($pipeClass)) {
            $mysteriousPipe = new $pipeClass($model);

            if ($mysteriousPipe instanceof Pipe) {
                return $mysteriousPipe;
            }
        }

        return null;
    }
}
