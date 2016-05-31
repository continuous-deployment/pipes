<?php

namespace Pipes\Pipeline;

use Pipes\Models\PipeLog;
use Pipes\Models\Stream;
use Pipes\Pipeline\Pipes\Severity;
use Pipes\Pipeline\Traveler\Bag;

abstract class Pipe
{
    /**
     * Current stream the pipe is on
     *
     * @var \Pipes\Models\Stream
     */
    protected $stream;

    /**
     * Handles the incoming traveler and perform necessary action
     *
     * @param Bag $bag The data sent from the previous pipe.
     *
     * @return \Illuminate\Database\Eloquent\Model|array
     */
    abstract public function flowThrough(Bag $bag);

    /**
     * Gets the model related to this pipe
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function getModel();

    /**
     * Sets the stream this pipe is on
     *
     * @param  Stream $stream
     * @return self
     */
    public function setStream(Stream $stream)
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * Log what this pipe has done
     *
     * @param integer $severity The severity of the log
     * @param string  $message  Title/message of the log
     * @param string  $output   Any output of the pipe
     *
     * @return void
     */
    public function log($severity, $message = null, $output = null)
    {
        $pipeLog = new PipeLog();

        $pipeLog->severity = $severity;
        $pipeLog->message  = $message;
        $pipeLog->output   = $output;
        $pipeLog->stream()->associate($this->stream);
        $pipeLog->pipeable()->associate($this->getModel());

        $pipeLog->save();
    }
}
