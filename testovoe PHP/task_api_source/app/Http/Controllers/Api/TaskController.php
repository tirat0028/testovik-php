<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Notifications\TaskCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->paginate(10);
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());

        try {
            $request->user()->notify(new TaskCreated($task));
        } catch (\Exception $e) {
            Log::error("Email notification failed: " . $e->getMessage());
        }

        return (new TaskResource($task))
                ->response()
                ->header('Location', route('tasks.show', $task));
    }

    public function show(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->update($request->validated());
        
        return new TaskResource($task);
    }

    public function destroy(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->noContent();
    }
}
