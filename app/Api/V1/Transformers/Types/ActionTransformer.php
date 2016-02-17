<?php

namespace App\Api\V1\Transformers\Types;

use App\Api\V1\Transformers\Transformer;
use App\Models\Host;
use App\Models\Action;
use App\Models\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActionTransformer extends Transformer
{
    /**
     * The fully qualified class of the model to use when transforming
     *
     * @var string
     */
    protected $modelClass = Action::class;

    /**
     * Transforms the given data into the related model
     *
     * @param stdClass $data Data to transform
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function transform($data)
    {
        $action = parent::transform($data);

        if (isset($data->host)) {
            $hostData = $data->host;
            $host = new Host();
            if (isset($hostData->id)) {
                try {
                    $host = $host->findOrFail($hostData->id);
                } catch (ModelNotFoundException $exeception) {
                }
            // Only set host properties if this is a new host.
            // Don't want to be overriding existing host data, just in case.
            } else {
                if (isset($hostData->attributes)) {
                    $host->fill($hostData->attributes);
                }
            }

            $action->host()->associate($host);
            $action->save();
        }

        $commands = $data->commands;
        $commandModels = [];

        foreach ($commands as $command) {
            $commandModel = new Command();
            $commandModel->command = $command;
            $commandModel->save();

            $commandModels[] = $commandModel;
        }

        $action->commands()->saveMany($commandModels);

        return $action;
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
    public function attachRelationship(
        $relationshipName,
        Model $model,
        $relationshipData
    ) {
        if ($relationshipName === 'next') {
            $model->pipeable()->associate($relationshipData);
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
        $relationships = [];

        if (isset($data->next)) {
            $relationships['next'] = $data->next;
        }

        return $relationships;
    }
}
