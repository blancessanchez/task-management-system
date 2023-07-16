<?php

namespace App\Repositories;

use App\Models\Task;

interface TaskStatusRepositoryInterface
{
    /**
     * Update the specified task.
     *
     * @param array $status
     * @param int $id
     * @return Task
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($status, $id);
}