<?php

namespace App\Api\V1\Transformers\Types;

use App\Api\V1\Transformers\Transformer;

class ActionTransformer implements Transformer
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

        if (isset($data->next)) {
            $relationships['next'] = $data->next;
        }

        return $relationships;
    }
}
