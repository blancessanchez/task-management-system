<?php

namespace App\Repositories;

use App\Models\Task;

interface TaskRepositoryInterface
{
    /**
     * Retrieve all tasks.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Task[]
     */
    public function index();

    /**
     * Store a new task.
     *
     * @param array $data
     * @return Task
     */
    public function store(array $data);

    /**
     * Update the specified task.
     *
     * @param int $id
     * @param array $data
     * @return Task
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $data);

    /**
     * Delete the specified task.
     *
     * @param Task $task
     * @return void
     */
    public function destroy(Task $task);
}