<?php

namespace App\Hooks;

use Illuminate\Http\Request;

class ServiceRouter
{
    /**
     * Array of all the catchers registered to this pier
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Enrols a new handler onto the router
     *
     * @param  Handler $handler The catcher to enrol
     * @return void
     */
    public function enrol(Handler $handler)
    {
        $appName = $handler->getApplicationName();
        $key     = strtolower($appName);

        $this->handlers[$key] = $handler;
    }

    /**
     * Routes the request the correct service.
     *
     * @param Request $request Request hook.
     * @param string  $appName The name of the application that sent the hook
     *
     * @return mixed
     */
    public function route(Request $request, $appName)
    {
        if (!array_key_exists($appName, $this->handlers)) {
            return false;
        }

        /** @var \App\Hooks\Handler $handler */
        $handler = $this->handlers[$appName];

        if ($handler->wantsHook($request)) {
            return $handler->catchHook($request);
        }

        return false;
    }
}
