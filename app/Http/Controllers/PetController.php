<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewPetRequest;
use App\Services\PetService;
use Illuminate\Http\Request;

class PetController extends Controller
{
	public function __construct(
		protected PetService $petService
	) { }

    public function index()
    {
        //
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

    public function show(string $id)
    {
        //
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
