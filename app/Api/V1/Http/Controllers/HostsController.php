<?php

namespace App\Api\V1\Http\Controllers;

use App\Api\V1\Transformers\Model\HostTransformer;
use App\Models\Host;
use Illuminate\Http\Request;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\Serializer\JsonApiSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class HostsController extends Controller
{
    /**
     * Model to use for queries.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Validation rules for hosts
     *
     * @var array
     */
    protected $rules = [
        'host' => 'required|string',
        'port' => 'required|integer'
    ];

    /**
     * Constructor.
     *
     * @param Host $host Empty host model.
     */
    public function __construct(Host $host)
    {
        $this->model = $host;
    }

    /**
     * Gets all the host
     * GET /api/v1/host
     *
     * @return array
     */
    public function all()
    {
        $host = $this->model->all();
        $output = $this->createJsonApiOutput($host);

        return $output;
    }

    /**
     * Gets a single host.
     * GET /api/v1/hosts/1
     *
     * @return array
     */
    public function get($hostId)
    {
        $host = $this->model->find($hostId);
        $output = $this->createJsonApiOutput($host);

        return $output;
    }

    /**
     * Stores a new host.
     * POST /api/v1/hosts
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
            'host',
            'port'
        ]);

        $host = $this->model->firstOrCreate($data);

        return [
            'status' => 'success',
            'message' => 'Successfully created host.',
            'resource' => $this->createJsonApiOutput($host)
        ];
    }

    /**
     * Updates an existing host.
     * PATCH /api/v1/hosts/{hostId}
     *
     * @param  Request $request Request that has been made.
     *
     * @return array
     */
    public function update(Request $request, $hostId)
    {
        $this->validate(
            $request,
            $this->rules
        );

        $data = $request->only([
            'host',
            'port'
        ]);

        $host = $this->model->find($hostId);

        if ($host === null) {
            return $this->buildFailedResponse(
                'Could not find host with id of ' . $hostId
            );
        }

        $host->fill($data);
        $host->save();

        return [
            'status' => 'success',
            'message' => 'Successfully created host.',
            'resource' => $this->createJsonApiOutput($host)
        ];
    }

    /**
     * Deletes the host using the given host id.
     * DELETE /api/v1/hosts/1/delete
     *
     * @param  integer $hostId Host id to delete.
     *
     * @return array
     */
    public function delete($hostId)
    {
        $host = $this->model->find($hostId);

        if ($host === null) {
            return $this->buildFailedResponse(
                'Could not find host with id of ' . $hostId
            );
        }

        $host->delete();

        return [
            'status' => 'success',
            'message' => 'Successfully deleted host.'
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
        $transformer = new HostTransformer();

        if ($resource instanceof Collection) {
            $resource = new ResourceCollection(
                $resource,
                $transformer,
                'host'
            );
        } else {
            $resource = new Item(
                $resource,
                $transformer,
                'host'
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
