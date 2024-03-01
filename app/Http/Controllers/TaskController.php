<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::paginate();

        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taskStatuses = TaskStatus::all();
        $users = User::all();
        //$labels = Label::all();
        return view('task.create', compact(
            'taskStatuses',
            'users',
            //'labels'//
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => 'required|unique:tasks',
                'status_id' => 'required|exists:task_statuses,id',
                'description' => 'nullable|string',
                'assigned_to_id' => 'nullable|integer',
            ],
            [
                'name.unique' => __('validation.task.unique')
            ]
        );

        $validated['created_by_id'] = Auth::id();

        $task = new Task();
        $task->fill($validated);
        $task->save();
        flash(__('flashes.tasks.store.success'))->success();

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $taskStatuses = TaskStatus::all();
        $users = User::all();
        //$labels = Label::all();
        return view('task.edit', compact(
            'task',
            'taskStatuses',
            'users',
            // 'labels'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => 'required|unique:tasks,name,' . $task->id,
                'description' => 'nullable|string',
                'assigned_to_id' => 'nullable|integer',
                'status_id' => 'required|integer',
            ],
            [
                'name.unique' => __('validation.task.unique')
            ]
        );

        $task->fill($validated);
        $task->save();
        flash(__('flashes.tasks.updated'))->success();

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        flash(__('flashes.tasks.deleted'))->success();

        return redirect()->route('tasks.index');
    }
}