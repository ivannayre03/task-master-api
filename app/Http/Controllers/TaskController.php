<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResourceCollection;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function index() // GET
    {
        $user = auth()->user();
        $tasks = Task::where('user_id', $user->id)->where('status', '!=', 'delete')->get();

        return new TaskResourceCollection($tasks);
    }

    public function store(Request $request) // POST
    {
        $user = auth()->user();
        $data = $request->all();
        $data['user_id'] = $user->id;

        $newTask = Task::create($data);

        return new TaskResource($newTask);
    }

    public function update($id, Request $request) // tasks/{id} PUT
    {
        $task = Task::find($id);
        $task->status = $request->status;
        $task->save();

        return new TaskResource($task);
    }

    public function destroy($id) // tasks/{id} DELETE
    {
        $task = Task::find($id);
        $task->status = "delete";
        $task->save();

        return response()->json(['message' => "Task successfully deleted."]);
    }
}
