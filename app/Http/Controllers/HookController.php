<?php

namespace Pipes\Http\Controllers;

use Pipes\Models\Stream;
use Pipes\Models\Project;
use Pipes\Pipeline\Pipeline;
use Pipes\Pipeline\Traveler\Traveler;
use Illuminate\Http\Request;

class HookController extends Controller
{
    /**
     * Handle incomming hooks.
     *
     * @param Request $request Incomming request object
     * @param string  $appName Name of the application the hook came from
     *
     * @return Response
     */
    public function recieve(Request $request, $appName)
    {
        /** @var \Pipes\Hooks\ServiceRouter $serviceRouter */
        $serviceRouter = app('ServiceRouter');
        $result = $serviceRouter->route($request, $appName);

        if ($result === false) {
            \Log::error('Unable to handle request ' . $request->getUri());
            \Log::error(json_encode($request->all(), JSON_PRETTY_PRINT));

            return [
                'status'  => 'Failure',
                'message' => 'Unable to handle this type of request',
            ];
        }

        if (isset($result['project']) &&
            $result['project'] instanceof Project
        ) {
            $this->processProject($result);
        }

        return [
            'status' => 'Success',
        ];
    }

    /**
     * Processes the project to send
     *
     * @param array $result Result from the catchers
     *
     * @return void
     */
    protected function processProject($result)
    {
        /** @var \Pipes\Models\Project $project */
        $project = $result['project'];
        $conditions = $project->conditions;
        foreach ($conditions as $condition) {
            $pipeline = new Pipeline();
            $stream = new Stream();
            $stream->project()->associate($project);
            $stream->pipeable()->associate($condition);
            $stream->save();
            $traveler = new Traveler($stream);
            $traveler->bag->give($result);

            $pipeline
                ->send($traveler)
                ->flow($stream);
        }
    }
}
