<?php

namespace App\Api\V1\Transformers\Model;

use App\Models\Project;
use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    /**
     * Transforms the project in a suitable array format

     * @param  Project $project Project to transform.
     *
     * @return array
     */
    public function transform(Project $project)
    {
        return $project->toArray();
    }
}
