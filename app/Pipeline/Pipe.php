<?php

namespace App\Pipeline;

interface Pipe
{
    /**
     * Handles the incoming request
     *
     * @param  mixed $traveler The data sent from the previous pipe.
     * @return mixed
     */
    public function handle($data);
}
