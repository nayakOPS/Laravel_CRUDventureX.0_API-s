<?php
namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

/*
The 3A's of testing: Arrange, Act, and Assert.

Arrangment: setting up data or environment
Action: performing actions or calling methods
Assertion: verifying the results/ expected outcome

*/

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    // reset the database after each test

    public function test_register_user()
    {
        // Arrangement(Data Setup):
        // postJson method is used to send a POST request to the given URI with a JSON payload.

        // Action triggered when register() is called in AuthController
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assertion(Verifying Result):
        // verifies the response staus is 201 and the expected JSON messsage is matched
        $response->assertStatus(201)
                 ->assertJson(['message' => 'User registered successfully. Please Log in to get your access token']);

        // verifying the users table in the db contains the newly registered users by checking that the email exists
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    public function test_login_user()
    {
        // Arrangement
        // User::factory()->create() creates a new user in the test_database
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
            // this mocks a user that's already existed/registered, simulating an authenticated user attempting to log in
        ]);

        // Action
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        // Assertion a.k.a Verifying Result
        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'token']);
    }

    // Example of Bad Path Testing
    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Unauthorized']);
    }

    // Good Path Testing
    public function test_logout_user()
    {
        // Arrangement : (Data Setup)
        // Sanctum::actingAs() is used to authenticate the user before the test runs

        // simulation for logged in user performing the logout action
        Sanctum::actingAs(
            User::factory()->create(),
            // creates a user and user is authenticated with sanctum token
            ['*']
        );

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'You have been successfully logged out']);
    }

    // E.g: Edge Case Testing
    // Bad Path Testing
    public function test_register_user_with_missing_fields()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'password']);
    }

    // Bad Path Testing
    public function test_register_user_with_invalid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_register_user_with_short_password()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);
    }

    public function test_register_user_with_mismatched_passwords()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_register_user_with_existing_email()
    {
        User::factory()->create([
            'email' => 'johndoe@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_login_with_unregistered_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'unregistered@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
                ->assertJson(['error' => 'Unauthorized']);
    }

    public function test_password_reset_request()
    {
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
        ]);

        $response = $this->postJson('/api/password/email', [
            'email' => 'johndoe@example.com',
        ]);

        $response->assertStatus(200)
                ->assertJson(['message' => 'We have emailed your password reset link!']);
    }

    public function test_logout_with_invalid_token()
    {
        $invalidToken = 'someInvalidTokenHere';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $invalidToken,
        ])->postJson('/api/logout');

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthenticated.']);
    }


    public function test_register_user_without_password_confirmation()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);
    }

    public function test_register_user_without_name()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

    public function test_register_user_with_malformed_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'invalidemail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_logout_without_authentication()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401)
                ->assertJson(['message' => 'Unauthenticated.']);
    }

}
