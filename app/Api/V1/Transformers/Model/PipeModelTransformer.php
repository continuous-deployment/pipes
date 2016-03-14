<?php

namespace App\Api\V1\Transformers\Model;

use App\Models\Action;
use App\Models\Condition;
use App\Models\Splitter;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\TransformerAbstract;

class PipeModelTransformer extends TransformerAbstract
{
    /**
     * Transforms a pipe model into an array format.

     * @param Model|null $model Pipeable to transform.
     *
     * @return array
     */
    public function transformPipeable($model)
    {
        if ($model === null) {
            return null;
        }

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());

        $resource = null;

        if ($model instanceof Splitter) {
            $transformer = new SplitterTransformer();
            $resource    = new Item(
                $model,
                $transformer,
                'splitter'
            );
        }

        if ($model instanceof Action) {
            $transformer = new ActionTransformer();
            $resource    = new Item(
                $model,
                $transformer,
                'action'
            );
        }

        if ($model instanceof Condition) {
            $transformer = new ConditionTransformer();
            $resource    = new Item(
                $model,
                $transformer,
                'condition'
            );
        }

        $output = $manager->createData($resource)->toArray();
        $output = $this->transferRelationshipsOutOfAttributes($output);

        return $output;
    }

    /**
     * Hack to pull out the relationships from the attributes array.
     *
     * @param  array $output Output of the transformer
     *
     * @return array
     */
    public function transferRelationshipsOutOfAttributes($output)
    {
        $data = $output['data'];
        foreach ($data['attributes']['relationships'] as $key => $value) {
            if ($value === null) {
                continue;
            }
            $data[$key] = $value;
        }
        unset($data['attributes']['relationships']);

        return $data;
    }
}
