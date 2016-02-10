<?php

namespace App\Api\V1\Http\Controllers;

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
        $data = $request->getContent();

        dd(json_decode($data));

        return response()->json([
            'message' => 'Successfully added pipeline',
            'status'  => 'Success'
        ], 200);
    }
}
