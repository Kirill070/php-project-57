<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskStatus;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private TaskStatus $taskStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->taskStatus = TaskStatus::factory()->create();
    }


    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertOk();
    }

    public function testStore()
    {
        $data = ['name' => 'TaskStatus'];

        $response = $this->post(route('task_statuses.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testUpdate()
    {
        $data = ['name' => 'NewTaskStatus'];

        $response = $this->patch(route('task_statuses.update', ['task_status' => $this->taskStatus]), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testDelete()
    {
        $response = $this->delete(route('task_statuses.destroy', ['task_status' => $this->taskStatus]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseMissing('labels', ['id' => $this->taskStatus->id]);
    }
}
