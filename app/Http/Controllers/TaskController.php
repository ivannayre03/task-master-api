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
        $tasks = Task::get();

        return new TaskResourceCollection($tasks);
    }

    public function store(Request $request)// POST
    {
        $data = $request->all(); // { title, description, status, etc.. }

        Task::create($data);

        return response()->json(['message' => 'Task successfully created.']);
    }

    public function update($id, Request $request) // tasks/{id} PUT
    {
        $task = Task::find($id);
        $task->status = $request->status;
        $task->save();

        return new TaskResource($task);
    }
}
