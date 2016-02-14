<?php

namespace App\Api\V1\Http\Controllers;

use App\Api\V1\Interpreter;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Storing a new pipeline
     *
     * @return JsonResponse
     */
    public function pipeline(Request $request)
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData);

        $interpreter = new Interpreter();
        $interpreter->validatePipelineRequest($data);

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
