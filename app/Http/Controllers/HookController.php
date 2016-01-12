<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;

class HookController extends Controller
{
    /**
     * Handle incomming hooks.
     *
     * @param  int  $id
     * @return Response
     */
    public function recieve(Request $request)
    {
      $data = $request->all();
      Log::debug(print_r($data, true));

      if(array_key_exists('event_name', $data) && $data['event_name'] == 'project_create') {
        // store new project in database
        $project_name = explode('/', $data['path_with_namespace']);
        $namespace = $project_name[0];
        $name = $project_name[1];
        $project = new Project;
        $project->name = $name;
        $project->group = $namespace;
        $project->save();
      }

      return $data;
    }
}
