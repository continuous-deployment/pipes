<?php

namespace App\Api\V1\Transformers\Types;

use App\Api\V1\Transformers\Transformer;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $condition = new Condition();

        if (isset($data->id)) {
            try {
                $condition = $condition->find($data->id);
            } catch (ModelNotFoundException $exception) {
                return null;
            }
        }

        if (isset($data->attributes)) {
            $condition->fill(
                (array) $data->attributes
            );
        }

        // $condition->save();

        return $condition;
    }

    /**
     * Attaches a relationship to the model
     *
     * @param  String    $relationshipName  Name of relationship in request
     * @param  Model     $model             Model that has the relationships
     * @param  Model     $relationshipModel Model to associate
     *
     * @return Model
     */
    public function attachRelationship(
        $relationshipName,
        Model $model,
        Model $relationshipModel
    ) {
        if ($relationshipName === 'success') {
            $model->successPipeable()->associate($relationshipModel);
        }

        if ($relationshipName === 'failure') {
            $model->failurePipeable()->associate($relationshipModel);
        }

        return $model;
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
