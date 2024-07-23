<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\Pet\Breed;
use App\Models\Pet\Specie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $response = json_decode($this->postJson('/api/auth/login', [
            'email' => $this->user->email,
            'password' => 'password123'
        ])->getContent(), true);

        $this->token = $response['access_token'];
    }

    public function testIfAnUserCantListYourPetsBeforeStoreSomePet(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->get('/api/pets/my_pets');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Você ainda não tem nenhum animal de estimação cadastrado.']);
    }

    public function testIfAnUserCanCreateAPet(): void
    {
        $petData = [
            'name' => 'trovao',
            'age' => '6',
            'breed' => 'pastor alemao',
            'specie' => 'cachorro'
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->post('/api/pets', $petData);

        $response->assertStatus(201)
            ->assertJson(['message' => "Your Pet has been created successfully."]);
    }

    public function testIfAnUserCanSeeYourPet(): void
    {
        $petData = [
            'name' => 'trovao',
            'age' => '6',
            'breed' => 'pastor alemao',
            'specie' => 'cachorro'
        ];

        $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->post('/api/pets', $petData);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->get('/api/pets/2');

        $response->assertStatus(200);
    }

    public function testIfAnUserCantSeeAMissedPet(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->get('/api/pets/2');

        $response->assertStatus(404)
            ->assertJson(['message' => 'O animal que você está procurando não existe, ou foi removido.']);
    }

    public function testIfAnUserCantSeeAnotherUsersPet(): void
    {
        $userTwo = User::factory()->state(['email' => 'test222@gmail.com'])->create();
        $specie  = Specie::create(['name' => 'cachorro']);
        $breed   = Breed::create(['name' => 'pinsher', 'specie_id' => $specie->id]);

        $pet = Pet::create([
            'name' => 'Tom',
            'age' => 2,
            'user_id' => $userTwo->id,
            'specie_id' => $specie->id,
            'breed_id'  => $breed->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->get('/api/pets/' . $pet->id);

        $response->assertStatus(404)
            ->assertJson(['message' => 'O animal que você está tentando visualizar, não pertence a você.']);
    }
}
