<?php

namespace Pipes\Api\V1\Transformers\Types;

use Pipes\Api\V1\Transformers\Transformer;
use Pipes\Models\Condition;
use Illuminate\Database\Eloquent\Model;

class ConditionTransformer extends Transformer
{
    /**
     * The fully qualified class of the model to use when transforming
     *
     * @var string
     */
    protected $modelClass = Condition::class;

    /**
     * Attaches a relationship to the model
     *
     * @param String $relationshipName Name of relationship in request
     * @param Model  $model            Model that has the relationships
     * @param mixed  $relationshipData Model(s) to associate
     *
     * @return Model
     */
    public function attachRelationship(
        $relationshipName,
        Model $model,
        $relationshipData
    ) {
        if ($relationshipName === 'success') {
            $model->successPipeable()->associate($relationshipData);
        }

        if ($relationshipName === 'failure') {
            $model->failurePipeable()->associate($relationshipData);
        }

        $model->save();

        return $model;
    }

    /**
     * Gets the next pipes in the data given
     *
     * @param stdClass $data Data to extract pipes from
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
