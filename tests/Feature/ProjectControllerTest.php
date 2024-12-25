<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user to authenticate the requests
        $this->user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Get the API token for authentication
        $this->token = $this->getAuthToken($this->user);
    }

    private function getAuthToken($user)
    {
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        return $response->json('token');
    }

    /** @test */
    public function can_create_project()
    {
        $data = [
            'name' => 'Test Project',
            'description' => 'This is a test project description.',
            'imgLink' => 'https://example.com/image.jpg',
        ];

        $response = $this->postJson(route('projects.store'), $data, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'imgLink',
            ]
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
        ]);
    }

    /** @test */
    public function can_view_project()
    {
        $project = Project::factory()->create();

        $response = $this->getJson(route('projects.show', $project->id), [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'imgLink',
            ]
        ]);
    }

    /** @test */
    public function can_view_all_projects()
    {
        $projects = Project::factory()->count(3)->create();

        $response = $this->getJson(route('projects.index'), [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'imgLink',
                ]
            ]
        ]);
    }

    /** @test */
    public function can_update_project()
    {
        $project = Project::factory()->create();

        $data = [
            'name' => 'Updated Project',
            'description' => 'Updated project description.',
            'imgLink' => 'https://example.com/updated-image.jpg',
        ];

        $response = $this->putJson(route('projects.update', $project->id), $data, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'imgLink',
            ]
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Updated Project',
        ]);
    }

    /** @test */
    public function can_delete_project()
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson(route('projects.destroy', $project->id), [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Project deleted successfully']);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }
}
