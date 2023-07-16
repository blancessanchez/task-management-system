<?php

namespace App\Repositories;

use App\Models\Task;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    /**
     * Get all tasks.
     *
     * @return Illuminate\Database\Eloquent\Collection|Task[]
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Store a new task.
     *
     * @param array $data
     * @return Task
     */
    public function store(array $data)
    {
        return Task::create($data);
    }

    /**
     * Update the specified task.
     *
     * @param int $id
     * @param array $data
     * @return Task
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);

        return $task;
    }

    /**
     * Delete the specified task.
     *
     * @param Task $task
     * @return void
     */
    public function destroy(Task $task)
    {
        $task->delete();
    }
}