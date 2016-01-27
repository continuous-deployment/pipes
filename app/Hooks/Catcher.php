<?php

namespace App\Hooks;

use Illuminate\Http\Request;

interface Catcher
{
    /**
     * Returns the name of the application the catcher is for
     *
     * @return string
     */
    public function getApplicationName();

    /**
     * Initial function to be called when a hooks comes in
     *
     * @param  Request $request The request object that was sent
     * @return void
     */
    public function catchHook(Request $request);

    /**
     * A check to see if this catcher wants the received hook
     *
     * @param  Request $request The request object that was sent
     * @return boolean
     */
    public function wantsHook(Request $request);
}
