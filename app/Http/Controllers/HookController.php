<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Pipeline\Pipeline;
use App\Pipeline\Traveler;
use Illuminate\Http\Request;

class HookController extends Controller
{
    /**
     * Handle incomming hooks.
     * @param Request $request Incomming request object
     * @param string  $appName Name of the application the hook came from
     *
     * @return Response
     */
    public function recieve(Request $request, $appName)
    {
        /** @var \App\Hooks\Pier $pier */
        $pier   = app('Pier');
        $result = $pier->sendRequestToCatcher($request, $appName);

        if ($result === false) {
            \Log::error('Unable to handle request ' . $request->getUri());
            \Log::error(json_encode($request->all(), JSON_PRETTY_PRINT));

            return [
                'status'  => 'Failure',
                'message' => 'Unable to handle this type of request',
            ];
        }

        $traveler = new Traveler();
        $traveler->give($result);

        if (isset($result['project'])
            && $result['project'] instanceof Project
        ) {
            /** @var \App\Models\Project $project */
            $project = $result['project'];
            $this->processProject($project, $traveler);
        }

        return [
            'status' => 'Success',
        ];
    }

    /**
     * Processes the project to send
     *
     * @param Project  $project  Project model
     * @param Traveler $traveler Traveler to send down pipeline
     *
     * @return void
     */
    protected function processProject($project, $traveler)
    {
        $conditions = $project->conditions;
        foreach ($conditions as $condition) {
            $pipeline = new Pipeline();
            $pipeline
                ->send($traveler)
                ->startWithModel($condition);
        }
    }
}
