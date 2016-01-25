<?php

namespace App\Hooks;

use Illuminate\Http\Request;

class Pier
{
    /**
     * Array of all the catchers registered to this pier
     *
     * @var array
     */
    protected $catchers = [];

    /**
     * Enrols a new catcher onto the pier
     *
     * @param  Catcher $catcher The catcher to enrol
     * @return void
     */
    public function enrol(Catcher $catcher)
    {
        $appName = $catcher->getApplicationName();
        $key     = strtolower($appName);

        $this->catchers[$key] = $catcher;
    }

    /**
     * Sees if there's a suitable catcher for the given request
     *
     * @param Request $request Request hook
     *
     * @return mixed
     */
    public function sendRequestToCatcher(Request $request, $appName)
    {
        if (!array_key_exists($appName, $this->catchers)) {
            return false;
        }

        /** @var \App\Hooks\Catcher $catcher */
        $catcher = $this->catchers[$appName];

        if ($catcher->wantsHook($request)) {
            return $catcher->catchHook($request);
        }

        return false;
    }
}
