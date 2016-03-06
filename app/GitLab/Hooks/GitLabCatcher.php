<?php

namespace App\GitLab\Hooks;

use App\GitLab\Hooks\Events\ProjectCreateEvent;
use App\GitLab\Hooks\Events\PushEvent;
use App\GitLab\Hooks\Events\CIBuildEvent;
use App\Hooks\Catcher;
use Illuminate\Http\Request;

class GitLabCatcher implements Catcher
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
     * @var \App\Hooks\Event
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
     * Returns the name of the application the catcher is for
     *
     * @return string
     */
    public function getApplicationName()
    {
        return 'GitLab';
    }

    /**
     * Initial function to be called when a hooks comes in
     *
     * @param  Request $request The request object that was sent
     * @return void
     */
    public function catchHook(Request $request)
    {
        return $this->chosenEvent->process($request);
    }

    /**
     * A check to see if this catcher wants the received hook
     *
     * @param  Request $request The request object that was sent
     * @return boolean
     */
    public function wantsHook(Request $request)
    {
        /** @var \App\Hooks\Event $event */
        foreach ($this->events as $event) {
            if ($event->canProcessRequest($request)) {
                $this->chosenEvent = $event;

                return true;
            }
        }

        return false;
    }
}
