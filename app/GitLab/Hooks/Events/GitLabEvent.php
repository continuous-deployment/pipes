<?php

namespace App\GitLab\Hooks\Events;

use Illuminate\Http\Request;

abstract class GitLabEvent
{
    /**
     * Key to look for in the request payload.
     * This can be a dot separate to look into arrays
     * contained within the initial array.
     *
     * @var string
     */
    protected $eventKey;

    /**
     * The value that should match what is returned from
     * the $eventKey
     *
     * @var mixed
     */
    protected $eventValue;

    /**
     * Returns whether this event is process the request data
     * and store it into the database if necessary
     *
     * @param  Request $request Request object for hook
     * @return boolean
     */
    public function canProcessRequest(Request $request)
    {
        $hasEventKey = $request->has($this->eventKey);
        if ($hasEventKey === false) {
            return false;
        }

        $isCorrectEvent = $request->get($this->eventKey) == $this->eventValue;

        return $isCorrectEvent;
    }
}
