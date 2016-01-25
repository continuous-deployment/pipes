<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class HookController extends Controller
{
    /**
     * Handle incomming hooks.
     *
     * @param int $id
     *
     * @return Response
     */
    public function recieve(Request $request, $appName)
    {
        $data = $request->all();
        Log::debug(print_r($data, true));

        /** @var \App\Hooks\Pier $pier */
        $pier = app('Pier');
        $pier->sendRequestToCatcher($request, $appName);

        return $data;
    }
}
