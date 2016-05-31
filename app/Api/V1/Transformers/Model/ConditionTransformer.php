<?php

namespace Pipes\Api\V1\Transformers\Model;

use Pipes\Models\Condition;

class ConditionTransformer extends PipeModelTransformer
{
    /**
     * Transforms the condition in a suitable array format

     * @param Condition $condition Condition to transform.
     *
     * @return array
     */
    public function transform(Condition $condition)
    {
        return [
            'id'    => $condition->id,
            'relationships' => [
                'success' => $this->transformPipeable($condition->success_pipeable),
                'failure' => $this->transformPipeable($condition->failure_pipeable),
            ],
            'type'     => $condition->type,
            'field'    => $condition->field,
            'operator' => $condition->operator,
            'value'    => $condition->value,
        ];
    }
}
