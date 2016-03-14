<?php

namespace App\Api\V1\Transformers\Model;

use App\Models\Splitter;

class SplitterTransformer extends PipeModelTransformer
{
    /**
     * Transforms the splitter in a suitable array format

     * @param Splitter $splitter Splitter to transform.
     *
     * @return array
     */
    public function transform(Splitter $splitter)
    {
        return [
            'id'    => $splitter->id,
            'relationships' => [
                'splits' => $splitter->splits->map(function ($item) {
                    return $this->transformPipeable($item->pipeable);
                })->toArray()
            ],
        ];
    }
}
