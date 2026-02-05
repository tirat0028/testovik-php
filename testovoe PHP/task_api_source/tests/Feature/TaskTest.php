<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'title' => 'New Task',
            'status' => 'pending',
            'due_date' => '2024-12-31',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'New Task');
        
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_user_cannot_access_other_users_tasks()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $task = Task::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(404); // Should be 404 because of findOrFail scope or 403 if using policies
    }
}
