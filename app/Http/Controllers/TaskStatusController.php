<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Repositories\TaskStatusRepositoryInterface;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    /**
     * The task repository instance.
     *
     * @var TaskStatusRepositoryInterface
     */
    private $taskStatusRepository;

    /**
     * Create a new TaskStatusController instance.
     *
     * @param TaskStatusRepositoryInterface $taskRepository
     */
    public function __construct(TaskStatusRepositoryInterface $taskStatusRepository)
    {
        $this->taskStatusRepository = $taskStatusRepository;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskStatusRequest $request, $taskId)
    {
        $updatedTask = $this->taskStatusRepository->update($request->validated(), $taskId);

        return $updatedTask;
    }
}
