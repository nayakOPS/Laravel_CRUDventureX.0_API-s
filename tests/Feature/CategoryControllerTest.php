<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticatedUser()
    {
        $user = User::factory()->create(); // Create a test user
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password', // Make sure the password matches the one you set in the factory
        ]);
        $token = $response->json('token');

        // Set the authorization header for future requests
        return $token;
    }

    public function test_can_create_category()
    {
        // Create a user and generate a token for them
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->plainTextToken;

        // Prepare the data for category creation
        $data = [
            'name' => 'Category Name',
            'description' => 'Category Description',
        ];

        // Send the request with the token in the Authorization header
        $response = $this->postJson(route('categories.store'), $data, [
            'Authorization' => 'Bearer ' . $token, // Attach the token
        ]);

        // Assert the response
        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => ['id', 'name', 'description']]);
    }

    public function test_can_view_category()
    {
        // Create a user and generate a token for them
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->plainTextToken;

        // Assuming a category exists
        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.show', $category), [
            'Authorization' => 'Bearer ' . $token, // Attach the token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'description']]);
    }

    public function test_can_view_all_categories()
    {
        // Create a user and generate a token for them
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->plainTextToken;

        $response = $this->getJson(route('categories.index'), [
            'Authorization' => 'Bearer ' . $token, // Attach the token
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'description'],
            ],
        ]);
    }
}

