<?php

namespace App\Hooks;

use Illuminate\Http\Request;

interface Event
{
    /**
     * Returns whether this event is able to process the request data
     * and store it into the database if necessary
     *
     * @param  Request $request Request object for hook
     * @return boolean
     */
    public function canProcessRequest(Request $request);

    /**
     * Perform the processing needed on the request
     *
     * @param  Request $request Request object for hook
     * @return void
     */
    public function process(Request $request);
}
