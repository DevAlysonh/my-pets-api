<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
    * @OA\Post(
    *     path="/api/auth/register",
    *     summary="Register a User",
    *     description="Creates an User on the Database",
    *	  tags={"Authentication"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"name", "email", "password", "password_confirmation"},
    *             @OA\Property(
    *                 property="name",
    *                 description="User Name",
    *                 type="string"
    *             ),
    *             @OA\Property(
    *                 property="email",
    *                 description="User Email",
    *                 type="string",
    *                 format="email"
    *             ),
    *             @OA\Property(
    *                 property="password",
    *                 description="Password",
    *                 type="string",
    *                 format="password"
    *             ),
    *             @OA\Property(
    *                 property="password_confirmation",
    *                 description="Confirm Password",
    *                 type="string",
    *                 format="password"
    *             ),
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="User Created",
    *     ),
    * )
    */
    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password'])
        ]);

        $token = auth('api')->login($user);

        return response()->json(
            [
                'message' => 'User Created.',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ],
            201
        );
    }

    /**
    * @OA\Post(
    *     path="/api/auth/login",
    *     summary="Login",
    *     description="Login With a registered User",
    *	  tags={"Authentication"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"email", "password"},
    *             @OA\Property(
    *                 property="email",
    *                 description="User Email",
    *                 type="string",
    *                 format="email"
    *             ),
    *             @OA\Property(
    *                 property="password",
    *                 description="Password",
    *                 type="string",
    *                 format="password"
    *             ),
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Login success",
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *     ),
    * )
    */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
    * @OA\Get(
    *     path="/api/me",
    *     summary="Get User Data",
    *     description="Get the authenticated user data",
    *     tags={"Authentication"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="Authenticated User data",
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
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
    * @OA\Post(
    *     path="/api/logout",
    *     summary="Logout",
    *     description="Log out an user",
    *     tags={"Authentication"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="Logged Out",
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
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
    * @OA\Post(
    *     path="/api/refresh",
    *     summary="Refresh Token",
    *     description="Refresh a User Token before that expires",
    *     tags={"Authentication"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Response(
    *         response=200,
    *         description="Content with a new token will be returned",
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
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
