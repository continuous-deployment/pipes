<?php

namespace App\Pipeline;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class PipeFactory
{
    /**
     * Makes the correct pipe depending on the value given
     *
     * @param mixed $identifier This can be a Model or a class string
     *
     * @return \App\Pipeline\Pipe|null
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
     * Make a pipe from a model
     *
     * @param Model $model Model to be given to a pipe
     *
     * @return \App\Pipeline\Pipe|null
     */
    protected static function makeFromModel(Model $model)
    {
        $reflectionModel = new ReflectionClass($model);
        $className       = $reflectionModel->getShortName();
        $pipeClass       = '\App\Pipeline\Pipes\\' . $className . 'Pipe';

        if (class_exists($pipeClass)) {
            $mysteriousPipe = new $pipeClass($model);

            if ($mysteriousPipe instanceof Pipe) {
                return $mysteriousPipe;
            }
        }

        return null;
    }
}
