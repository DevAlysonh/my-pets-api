<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\Pet\Breed;
use App\Models\Pet\Specie;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class PetService
{
    public function createNewPet(array $apiData)
    {
        $petData = $this->buildNewPetData($apiData);

        $newPet = Pet::create([
            'name'      => $petData['name'],
            'age'       => (int)$petData['age'],
            'user_id'   => $petData['user_id'],
            'specie_id' => $petData['specie_id'],
            'breed_id'  => $petData['breed_id']
        ]);

        return $newPet;
    }

    public function getAllUserPets(): Collection
    {
        $userPets = auth()->user()->pets;

        if (empty($userPets->items)) {
            throw new Exception('Você ainda não tem nenhum animal de estimação cadastrado.');
        }

        return $userPets;
    }

    public function getPetProfile(string $petId): array
    {
        $pet = Pet::find($petId);

        if (!$pet) {
            throw new Exception('O animal que você está procurando não existe, ou foi removido.');
        }

        if (auth()->user()->cannot('view', $pet)) {
            abort(401, 'O animal que você está tentando visualizar, não pertence a você.');
        }

        return [
            'name'   => $pet->name,
            'age'    => $pet->age,
            'owner'  => $pet->user->only('id', 'name'),
            'breed'  => $pet->breed,
            'specie' => $pet->specie
        ];
    }

    private function buildNewPetData(array $apiData): array
    {
        $specie =  Specie::where('name', $apiData['specie'])->first();
        $breed  =  Breed::where('name', $apiData['breed'])->first();

        if (!$specie) {
            $specie = Specie::create(['name' => $apiData['specie']]);
        }

        if (!$breed) {
            $breed = Breed::create([
                'name' => $apiData['breed'],
                'specie_id' => $specie->id
            ]);

        }

        $apiData['specie_id'] = $specie->id;
        $apiData['breed_id']  = $breed->id;
        $apiData['user_id']   = auth()->user()->id;
        unset($apiData['breed'], $apiData['specie']);

        return $apiData;
    }
}
