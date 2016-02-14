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

        dd($interpreter->getErrorMessages());

        return response()->json([
            'message' => 'Successfully added pipeline',
            'status'  => 'Success'
        ], 200);
    }
}
