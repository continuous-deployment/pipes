<?php

namespace App\Api\V1\Transformers\Types;

use App\Api\V1\Transformers\Transformer;
use App\Models\Split;
use App\Models\Splitter;
use Illuminate\Database\Eloquent\Model;

class SplitterTransformer extends Transformer
{
    /**
     * The fully qualified class of the model to use when transforming
     *
     * @var string
     */
    protected $modelClass = Splitter::class;

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
        if ($relationshipName === 'splits') {
            $relationshipData = array_map(function ($item) {
                $split = new Split();
                $split->pipeable()->associate($item);
                $split->save();

                return $split;
            }, $relationshipData);

            $model->splits()->saveMany($relationshipData);
        }

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
        return [
            'splits' => $data->splits,
        ];
    }
}
