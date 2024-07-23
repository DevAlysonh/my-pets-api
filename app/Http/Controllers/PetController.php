<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewPetRequest;
use App\Services\PetService;
use Exception;

class PetController extends Controller
{
    public function __construct(
        protected PetService $petService
    ) {
    }

    /**
    * @OA\Get(
    *     path="/api/pets/my_pets",
    *     summary="Get User Pets",
    *     description="Get the authenticated user pets",
    *     tags={"My Pets"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="Will return all user pets",
    *  	   	  @OA\MediaType(
    *      	  	mediaType="application/json",
    *     	  )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *  	   	  @OA\MediaType(
    *      	  	mediaType="application/json",
    *     	  )
    *     ),
    * )
    */
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

    /**
    * @OA\Post(
    *     path="/api/pets",
    *     summary="Register a New Pet",
    *     description="Creates a new pet for authenticated user",
    *     security={{"bearerAuth":{}}},
    *	  tags={"My Pets"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"name", "age", "breed", "specie"},
    *             @OA\Property(
    *                 property="name",
    *                 description="Pet Name",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="age",
    *                 description="Pet Age",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="breed",
    *                 description="Pet Breed",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="specie",
    *                 description="Pet Specie (dog, cat, bird...)",
    *                 type="string"
    *             ),
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Pet Created",
    *  	   	  @OA\MediaType(
    *      	  	mediaType="application/json",
    *     	  )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *  	   	  @OA\MediaType(
    *      	  	mediaType="application/json",
    *     	  )
    *     ),
    * )
    */
    public function store(CreateNewPetRequest $request)
    {
        $data = $request->all();

        $pet = $this->petService->createNewPet($data);

        return response()->json([
            'message' => "Your Pet has been created successfully.",
            'pet' => $pet
        ], 201);
    }

    /**
    * @OA\Get(
    *     path="/api/pets/{petId}",
    *     summary="Get User Single Pet",
    *     description="Get the authenticated user single pet profile",
    *     tags={"My Pets"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(
    *         name="petId",
    *         in="path",
    *         required=true,
    *         description="ID of the pet to retrieve",
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Will return a pet profile",
    *  	   	  @OA\MediaType(
    *      	  	mediaType="application/json",
    *     	  )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *  	   	  @OA\MediaType(
    *      	  	mediaType="application/json",
    *     	  )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Pet not found",
    *  	   	  @OA\MediaType(
    *      	  	mediaType="application/json",
    *     	  )
    *     )
    * )
    */
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
}
