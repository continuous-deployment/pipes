<?php

namespace App\Api\V1\Transformers;

interface Transformer
{
    /**
     * Transforms the given data into the related model
     *
     * @param  stdClass $data Data to transform
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function transform($data);

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
    );

    /**
     * Gets the next pipes in the data given
     *
     * @param  stdClass $data Data to extract pipes from
     *
     * @return array
     */
    public function getNextPipesFromData($data);
}
