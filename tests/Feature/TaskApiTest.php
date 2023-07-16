<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all tasks through the API.
     *
     * @return void
     */
    public function testGetAllTasks()
    {
        $tasks = Task::factory(3)->create();

        $response = $this
            ->withHeaders(['X-API-Key' => 'abcd'])
            ->get('/api/tasks');

        $response->assertStatus(200);
        $responseData = $response->json();

        $this->assertIsArray($responseData);
        $this->assertCount(3, $responseData);

        $response->assertJsonStructure([['id', 'title', 'description', 'due_date', 'status']]);
    }

    /**
     * Test creating a task through the API.
     *
     * @return void
     */
    public function testCreateTask()
    {
        $newTaskData = [
            'title' => 'New Task',
            'description' => 'This is a new task',
            'due_date' => '2023-12-31',
            'status' => 1,
        ];

        $response = $this
            ->withHeaders(['X-API-Key' => 'abcd'])
            ->post('/api/tasks', $newTaskData);

        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'title', 'description', 'due_date', 'status', 'created_at', 'updated_at']);
        $this->assertDatabaseHas('tasks', $newTaskData);
    }
}
