<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
	use RefreshDatabase;

	protected User $user;
	protected array $loginData;

	public function setUp(): void
	{
		parent::setUp();

		$this->user = User::factory()->create();
		$this->loginData = [
            'email' => $this->user->email,
            'password' => 'password123',
        ];
	}

	public function testIfUserCanLoginWithValidCredentials()
	{
        $response = $this->postJson('/api/auth/login', $this->loginData);

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
        $wrongLoginData = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $wrongLoginData);

        $response->assertStatus(401)
                ->assertJson(['error' => 'Unauthorized']);
	}

	public function testIfUserCanLogoutWhenAreAuthenticated()
	{
        $response = $this->postJson('/api/auth/login', $this->loginData);
		$authData = json_decode($response->getContent());

		$logedOut = $this->withHeaders([
			'Authorization' => "Bearer $authData->access_token",
		])->post('/api/logout');

		$logedOut->assertStatus(200)
			->assertJson(['message' => 'Successfully logged out']);
	}

	public function testIfUserCantLogoutWhenAreUnauthenticated()
	{
		$logedOut = $this->withHeaders([
			'Authorization' => "Bearer xxx",
		])->postJson('/api/logout');

        $logedOut->assertStatus(401)
                ->assertJson(['message' => 'Unauthenticated.']);
	}

	public function testIfUserCanGetYourDataWhenAreAuthenticated()
	{
        $response = $this->postJson('/api/auth/login', $this->loginData);
		$authData = json_decode($response->getContent());

		$logedOut = $this->withHeaders([
			'Authorization' => "Bearer $authData->access_token",
		])->get('/api/me');
		
		$logedOut->assertStatus(200)
			->assertJson([
				'id' => $this->user->id,
				'name' => $this->user->name,
				'email' => $this->user->email
			]);
	}

	public function testIfUserCantGetYourDataWhenAreUnauthenticated()
	{
		$logedOut = $this->withHeaders([
			'Authorization' => "Bearer xxx",
		])->getJson('/api/me');
		
		$logedOut->assertStatus(401)
			->assertJson(['message' => 'Unauthenticated.']);
	}
}
