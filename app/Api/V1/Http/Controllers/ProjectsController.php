<?php

namespace App\Api\V1\Http\Controllers;

use App\Api\V1\Transformers\Model\ProjectTransformer;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\Serializer\JsonApiSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProjectsController extends Controller
{
    /**
     * Model to use for queries.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Validation rules for projects
     *
     * @var array
     */
    protected $rules = [
        'project_id' => 'required|integer',
        'name' => 'required|string',
        'group' => 'required|string',
        'url' => 'required|url'
    ];

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
        $output = $this->createJsonApiOutput($projects);

        return $output;
    }

    /**
     * Gets a single project.
     * GET /api/v1/projects/1
     *
     * @param  integer $projectId Project id to get
     *
     * @return array
     */
    public function get($projectId)
    {
        $project = $this->model
            ->find($projectId)
            ->with('conditions')
            ->first();
        $output = $this->createJsonApiOutput($project);

        return $output;
    }

    /**
     * Stores a new project.
     * POST /api/v1/projects
     *
     * @param  Request $request Request that has been made.
     *
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            $this->rules
        );

        $data = $request->only([
            'project_id',
            'name',
            'group',
            'url'
        ]);

        $project = $this->model->firstOrCreate($data);

        return [
            'status' => 'success',
            'message' => 'Successfully created project.',
            'resource' => $this->createJsonApiOutput($project)
        ];
    }

    /**
     * Updates an existing project.
     * PATCH /api/v1/projects/{projectId}
     *
     * @param  Request $request Request that has been made.
     *
     * @return array
     */
    public function update(Request $request, $projectId)
    {
        $this->validate(
            $request,
            $this->rules
        );

        $data = $request->only([
            'project_id',
            'name',
            'group',
            'url'
        ]);

        $project = $this->model->find($projectId);

        if ($project === null) {
            return $this->buildFailedResponse(
                'Could not find project with id of ' . $projectId
            );
        }

        $project->fill($data);
        $project->save();

        return [
            'status' => 'success',
            'message' => 'Successfully created project.',
            'resource' => $this->createJsonApiOutput($project)
        ];
    }

    /**
     * Deletes the project using the given project id.
     * DELETE /api/v1/projects/1/delete
     *
     * @param  integer $projectId Project id to delete.
     *
     * @return array
     */
    public function delete($projectId)
    {
        $project = $this->model->find($projectId);

        if ($project === null) {
            return $this->buildFailedResponse(
                'Could not find project with id of ' . $projectId
            );
        }

        $project->delete();

        return [
            'status' => 'success',
            'message' => 'Successfully deleted project.'
        ];
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
        $transformer = new ProjectTransformer();

        if ($resource instanceof Collection) {
            $resource = new ResourceCollection(
                $resource,
                $transformer,
                'project'
            );
        } else {
            $resource = new Item(
                $resource,
                $transformer,
                'project'
            );
        }

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());

        $output = $manager->createData($resource)->toArray();

        return $output;
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $errors
     * @return \Illuminate\Http\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        $message = 'Failed validation';

        return $this->buildFailedResponse($message, $errors);
    }

    /**
     * Builds a failed response.
     *
     * @param string $message Message to send.
     * @param array  $errors  Any errors associated to the failure.
     */
    protected function buildFailedResponse($message, $errors = [])
    {
        return new JsonResponse([
            'status' => 'failed',
            'message' => $message,
            'errors' => $errors
        ], 422);
    }
}
