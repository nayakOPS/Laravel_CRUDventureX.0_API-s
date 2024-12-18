<?php

namespace App\Http\Controllers\API;

use App\Http\Filters\ProjectFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Http\Requests\ProjectIndexRequest;

class ProjectController extends Controller
{
    public function index(ProjectIndexRequest $request)
    {
        $query = Project::query()->with(['categories', 'tasks']);
        $filter = new ProjectFilter($request, $query);
        // $projects = $filter->apply()->get();
        $projects = $filter->apply()->paginate(10);

        return ProjectResource::collection($projects);
    }

    // public function index()
    // {
    //     return ProjectResource::collection(Project::with(['categories', 'tasks'])->get());
    // }

    public function store(ProjectStoreRequest $request)
    {
        $project = Project::create($request->validated());

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        return new ProjectResource($project->load(['categories', 'tasks']));
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->update($request->validated());

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }
}
