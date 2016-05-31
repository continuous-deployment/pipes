<?php

namespace Pipes\GitLab\Hooks;

use Pipes\GitLab\Hooks\Events\CIBuildEvent;
use Pipes\GitLab\Hooks\Events\ProjectCreateEvent;
use Pipes\GitLab\Hooks\Events\PushEvent;
use Pipes\Hooks\Handler;
use Illuminate\Http\Request;

class GitLabHandler implements Handler
{
    /**
     * Events this catcher can receive
     *
     * @var array
     */
    protected $events = [];

    /**
     * Event that has been chosen to process the request
     *
     * @var \Pipes\Hooks\Event
     */
    protected $chosenEvent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events[] = new ProjectCreateEvent();
        $this->events[] = new PushEvent();
        $this->events[] = new CIBuildEvent();
    }

    /**
     * Returns the name of the application the handler is for
     *
     * @return string
     */
    public function getApplicationName()
    {
        return 'GitLab';
    }

    /**
     * Will handle the request creating any new models needed.
     *
     * @param  Request $request The request object that was sent
     * @return void
     */
    public function handleRequest(Request $request)
    {
        return $this->chosenEvent->process($request);
    }

    /**
     * A check to see if this handler wants the received hook
     *
     * @param  Request $request The request object that was sent
     * @return boolean
     */
    public function canHandleRequest(Request $request)
    {
        /** @var \Pipes\Hooks\Event $event */
        foreach ($this->events as $event) {
            if ($event->canProcessRequest($request)) {
                $this->chosenEvent = $event;

                return true;
            }
        }

        return false;
    }
}
