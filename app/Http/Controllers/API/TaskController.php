<?php

namespace App\Http\Controllers\API;

use App\Http\Filters\TaskFilter;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\TaskIndexRequest;

class TaskController extends Controller
{

    public function index(TaskIndexRequest $request)
    {
        // eager loading prevetns N+1 query problem by fetching related data
        $query = Task::query()->with(['project', 'assignedUser']); //Eager Loading Relationships
        $filter = new TaskFilter($request, $query);
        // $tasks = $filter->apply()->get(); //to get all responses
        $tasks = $filter->apply()->paginate(10); // Add pagination of 10

        return TaskResource::collection($tasks);
    }

    /*
        $task = Task::find($id);
        $task->load('project'); // Lazy loads project relationship
        $task->load('assignedUser'); // Lazy loads assigned user

    */

    /* public function index()
    {
        return TaskResource::collection(Task::with(['project', 'assignedUser'])->get());
    } */

    public function store(TaskStoreRequest $request)
    {
        $task = Task::create($request->validated());

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        // Eager loading specific relationships for detailed view
        return new TaskResource($task->load(['project', 'assignedUser']));
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.'], 200);
    }
}
