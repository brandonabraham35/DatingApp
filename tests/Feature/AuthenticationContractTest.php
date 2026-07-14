<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_user_with_location_and_returns_a_sanctum_token(): void
    {
        $response = $this->postJson('/api/register', [
            'role' => 'seeker',
            'username' => 'kampala-seeker',
            'email' => 'seeker@example.com',
            'password' => 'password',
            'location' => 'Kampala',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['access_token', 'token_type'])
            ->assertJsonPath('token_type', 'Bearer');

        $this->assertDatabaseHas('users', [
            'email' => 'seeker@example.com',
            'username' => 'kampala-seeker',
            'location' => 'Kampala',
            'role' => 'seeker',
        ]);
    }

    public function test_login_returns_a_token_for_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type'])
            ->assertJsonPath('token_type', 'Bearer');
    }

    public function test_logout_revokes_the_current_sanctum_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $response = $this->withToken($token->plainTextToken)
            ->postJson('/api/logout');

        $response->assertOk()
            ->assertJsonPath('message', 'Logged out successfully.');

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }
}
