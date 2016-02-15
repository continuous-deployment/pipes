<?php

namespace App\Api\V1\Transformers;

use ReflectionException;

class Factory
{
    /**
     * Array of transformers to not have to keep reinstantiating them
     *
     * @var array
     */
    protected $transformers = [];

    /**
     * Array of validators to not have to keep reinstantiating them
     *
     * @var array
     */
    protected $validators = [];

    /**
     * Make a transformer from the type given.
     *
     * @param string $type Type of transformer to make
     *
     * @return \App\Api\V1\Transformers\Transformer|null
     */
    public function make($type)
    {
        if (array_key_exists($type, $this->transformers)) {
            return $this->transformers[$type];
        }

        $className = 'App\Api\V1\Transformers\Types\\' .
            title_case($type) . 'Transformer';

        try {
            $transformer = app($className);
        } catch (ReflectionException $exception) {
            return null;
        }

        $this->transformers[$type] = $transformer;

        return $transformer;
    }

    /**
     * Make a transformer validator from the type given.
     *
     * @param string $type Type of validator to make
     *
     * @return \App\Api\V1\Transformers\Validator|null
     */
    public function makeValidator($type)
    {
        if (array_key_exists($type, $this->validators)) {
            return $this->validators[$type]->reset();
        }

        $className = 'App\Api\V1\Transformers\Validators\\' .
        title_case($type) . 'Validator';

        try {
            $validator = app($className);
        } catch (ReflectionException $exception) {
            return null;
        }

        $this->validators[$type] = $validator;

        return $validator;
    }
}
