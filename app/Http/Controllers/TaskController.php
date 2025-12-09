<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResourceCollection;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{

    // CRUD OPERATION LARAVEL API
    public function index() // GET
    {
        $tasks = Task::where('status', '!=', 'delete')->get();

        return new TaskResourceCollection($tasks);
    }

    public function store(Request $request)// POST
    {
        $data = $request->all(); // { title, description, status, etc.. }

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
