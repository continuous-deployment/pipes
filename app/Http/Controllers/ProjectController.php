<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Get all projects
     *
     * @return Json Response
     */
    public function all()
    {
        $projects = Project::all();

        return response()->json($projects);
    }

    /**
     * Get a project by its ID
     *
     * @param  integer $projectId Project id to get
     * @return Json    Response
     */
    public function get($projectId)
    {
        $project = Project::find($projectId);

        return response()->json($project);
    }
}
