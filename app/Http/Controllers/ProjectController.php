<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Project;

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
     * @return Json Response
     */
    public function get($project_id)
    {
        $project = Project::find($project_id);
        return response()->json($project);
    }
}