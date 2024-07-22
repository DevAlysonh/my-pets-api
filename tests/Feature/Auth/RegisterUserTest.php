<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function testIfWeCanCreateAnUser(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'testUser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User Created.']);
        $this->assertNotNull(User::first());
    }
}
