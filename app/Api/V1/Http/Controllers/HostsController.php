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
