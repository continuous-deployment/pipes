<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Pipeline\Pipeline;
use App\Pipeline\Traveler;
use Illuminate\Http\Request;
use Log;

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
        // $data = $request->all();
        // Log::debug(json_encode($data));
        // Log::debug(print_r($data, true));

        /** @var \App\Hooks\Pier $pier */
        $pier     = app('Pier');
        $result   = $pier->sendRequestToCatcher($request, $appName);
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
