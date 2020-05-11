<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function create(Request $request)
    {
        $task = Task::create([
            'name' => $request->name,
            'comment' => $request->comment,
            'deadline' => $request->deadline,
            'progress' => $request->progress,
            'state' => $request->state,
            'user_id' => $request->user
             ]);
        if ($task) {
            return response()->json([
                    "success" => true,
                    "task" => $task
                ]);
        } else {
            return response()->json(
                [
                    "success" => false
                ]
            );
        }
    }

    public function update(Request $request)
    {
        $task = Task::find($request->id);
        $task->name = $request->name;
        $task->comment = $request->comment;
        $task->deadline = $request->deadline;
        $task->progress = $request->progress;
        $task->state = $request->state;
        $task->user_id = $request->user;
        if ($task->save()) {
            return response()->json([
                    "success" => true,
                    "task" => $task
                ]);
        } else {
            return response()->json(
                [
                    "success" => false
                ]
            );
        }
    }

    public function delete(Request $request)
    {
        $task = Task::find($request->id);
        if ($task->delete()) {
            return response()->json([
                    "success" => true,
                    "task" => $task
                ]);
        } else {
            return response()->json(
                [
                    "success" => false
                ]
            );
        }
    }

    public function getAuthedUserTasks(Request $request)
    {
        return response()->json([
            "success" => true,
            "tasks" => $request->user()->getTasks()
        ]);
    }

    public function getTasksPerUser(Request $request){
        $user = User::find($request->id);
        return response()->json([
            "success" => true,
            "tasks" => $user->getTasks()
        ]);
    }

    
}
