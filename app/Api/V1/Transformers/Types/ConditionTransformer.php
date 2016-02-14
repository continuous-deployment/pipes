<?php

namespace App\Api\V1\Transformers\Types;

use App\Api\V1\Transformers\Transformer;

class ConditionTransformer implements Transformer
{
    /**
     * Transforms the given data into the related model
     *
     * @param  stdClass $data Data to transform
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function transform($data)
    {

    }

    /**
     * Gets the next pipes in the data given
     *
     * @param  stdClass $data Data to extract pipes from
     *
     * @return array
     */
    public function getNextPipesFromData($data)
    {
        $relationships = [];

        if (isset($data->success)) {
            $relationships['success'] = $data->success;
        }

        if (isset($data->failure)) {
            $relationships['failure'] = $data->failure;
        }

        return $relationships;
    }
}
