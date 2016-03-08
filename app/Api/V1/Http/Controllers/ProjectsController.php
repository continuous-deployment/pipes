<?php

namespace App\Api\V1\Http\Controllers;

use App\Api\V1\Transformers\Model\ProjectTransformer;
use App\Models\Project;
use Laravel\Lumen\Routing\Controller;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;

class ProjectsController extends Controller
{
    /**
     * Model to use for queries.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Constructor.
     *
     * @param Project $project Empty project model.
     */
    public function __construct(Project $project)
    {
        $this->model = $project;
    }

    /**
     * Gets all the projects
     * GET /api/v1/projects
     *
     * @return JsonResponse
     */
    public function all()
    {
        $projects = $this->model->all();

        $resource = new Collection(
            $projects,
            new ProjectTransformer(),
            'project'
        );

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());

        $output = $manager->createData($resource)->toArray();

        return $output;
    }
}
