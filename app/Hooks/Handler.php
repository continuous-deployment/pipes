<?php

namespace App\Hooks;

use Illuminate\Http\Request;

interface Handler
{
    /**
     * Returns the name of the application the handler is for
     *
     * @return string
     */
    public function getApplicationName();

    /**
     * Will handle the request creating any new models needed.
     *
     * @param  Request $request The request object that was sent
     * @return void
     */
    public function handleRequest(Request $request);

    /**
     * A check to see if this handler wants the received hook
     *
     * @param  Request $request The request object that was sent
     * @return boolean
     */
    public function canHandleRequest(Request $request);
}
