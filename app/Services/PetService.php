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

        if ($userPets->count = 0) {
            throw new Exception('Você ainda não tem nenhum animal de estimação cadastrado.');
        }

        return $userPets;
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
