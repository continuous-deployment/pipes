<?php

namespace Pipes\Api\V1\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Transformer
{
    /**
     * The fully qualified class of the model to use when transforming
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Transforms the given data into the related model
     *
     * @param stdClass $data Data to transform
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function transform($data)
    {
        $model = new $this->modelClass();

        if (isset($data->id)) {
            try {
                $model = $model->findOrFail($data->id);
            } catch (ModelNotFoundException $exception) {
            }
        }

        if (isset($data->attributes)) {
            $model->fill(
                (array) $data->attributes
            );
        }

        $model->save();

        return $model;
    }

    /**
     * Attaches a relationship to the model
     *
     * @param String $relationshipName Name of relationship in request
     * @param Model  $model            Model that has the relationships
     * @param mixed  $relationshipData Model(s) to associate
     *
     * @return Model
     */
    abstract public function attachRelationship(
        $relationshipName,
        Model $model,
        $relationshipData
    );

    /**
     * Gets the next pipes in the data given
     *
     * @param stdClass $data Data to extract pipes from
     *
     * @return array
     */
    abstract public function getNextPipesFromData($data);
}
