<?php

namespace App\Api\V1\Http\Controllers;

use App\Api\V1\Transformers\Model\ProjectTransformer;
use App\Models\Project;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
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
     * @return array
     */
    public function all()
    {
        $projects = $this->model->all();

        $resource = new Collection(
            $projects,
            new ProjectTransformer(),
            'project'
        );

        $output = $this->createJsonApiOutput($resource);

        return $output;
    }

    /**
     * Gets a single project.
     * GET /api/v1/projects/1
     *
     * @return array
     */
    public function get($projectId)
    {
        $project = $this->model->find($projectId);

        $resource = new Item(
            $project,
            new ProjectTransformer(),
            'project'
        );

        $output = $this->createJsonApiOutput($resource);

        return $output;
    }

    /**
     * Creates a Json Api output using the resource
     *
     * @param mixed $resource Resources to convert.
     *
     * @return array
     */
    protected function createJsonApiOutput($resource)
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());

        $output = $manager->createData($resource)->toArray();

        return $output;
    }
}
