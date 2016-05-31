<?php

namespace Pipes\Http\Api\V1\Controllers;

use Pipes\Api\V1\Interpreter;
use Pipes\Api\V1\Transformers\Model\PipeModelTransformer;
use Pipes\Http\Controllers\Controller;
use Pipes\Models\Condition;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\JsonResponse
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
