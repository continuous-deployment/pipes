<?php

namespace App\Api\V1\Http\Controllers;

use App\Api\V1\Interpreter;
use App\Api\V1\Transformers\Model\PipeModelTransformer;
use App\Api\V1\Transformers\Model\ConditionTransformer;
use App\Models\Condition;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Manager;

class PipelineController extends Controller
{
    /**
     * Get the pipeline
     * GET /api/v1/pipeline
     *
     * @param integer $conditionId Condition the pipeline starts with.
     *
     * @return array
     */
    public function pipeline($conditionId)
    {
        $condition = new Condition();
        $condition = $condition->find($conditionId);

        $pipeModelTransformer = new PipeModelTransformer();
        $output = $pipeModelTransformer->transformPipeable($condition);

        return $output;
    }

    /**
     * Storing a new pipeline
     * POST /api/v1/pipeline
     *
     * @return JsonResponse
     */
    public function storePipeline(Request $request)
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData);

        $interpreter = new Interpreter();
        $interpreter->parsePipelineRequest($data);

        $messages = $interpreter->getErrorMessages();

        if ($messages != []) {
            return response()->json($messages, 400);
        }

        return response()->json([
            'message' => 'Successfully added pipeline',
            'status'  => 'Success'
        ], 200);
    }
}
