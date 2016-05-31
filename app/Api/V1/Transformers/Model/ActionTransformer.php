<?php

namespace Pipes\Api\V1\Transformers\Model;

use Pipes\Models\Action;

class ActionTransformer extends PipeModelTransformer
{
    /**
     * Transforms the action in a suitable array format

     * @param Action $action Action to transform.
     *
     * @return array
     */
    public function transform(Action $action)
    {
        $relationships    = [];
        $commands = [];

        if ($action->pipeable !== null) {
            $relationships['next'] = $this->transformPipeable($action->pipeable);
        }

        if (is_array($action->commands)) {
            $commands = array_map(function ($command) {
                return $command->command;
            }, $action->commands);

            $relationships['commands'] = $commands;
        }

        return [
            'id'            => $action->id,
            'relationships' => $relationships,
            'type'          => $action->type,
            'host_id'       => $action->host_id,
        ];
    }
}
