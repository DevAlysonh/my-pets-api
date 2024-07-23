<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewPetRequest;
use App\Services\PetService;
use Exception;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct(
        protected PetService $petService
    ) {
    }

    public function index()
    {
        try {
            $pets = $this->petService->getAllUserPets();
            return response()->json(['user_pets' => $pets], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(CreateNewPetRequest $request)
    {
        $data = $request->all();

        $pet = $this->petService->createNewPet($data);

        return response()->json([
            'message' => "Your Pet has been created successfully.",
            'pet' => $pet
        ], 201);
    }

    public function show(string $petId)
    {
        try {
            $pet = $this->petService->getPetProfile($petId);
            return response()->json($pet, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
