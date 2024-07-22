<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
	use RefreshDatabase;

	protected User $user;

	public function setUp(): void
	{
		parent::setUp();

		$this->user = User::factory()->create();
	}

	public function testIfUserCanLoginWithValidCredentials()
	{
        $loginData = [
            'email' => $this->user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'access_token',
                    'token_type',
                    'expires_in',
                ]);

        $this->assertArrayHasKey('access_token', $response->json());
	}

	public function testIfUserCantLoginWithInvalidCredentials()
	{
        $loginData = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(401)
                ->assertJson(['error' => 'Unauthorized']);
	}
}
