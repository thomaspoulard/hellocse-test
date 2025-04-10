<?php

namespace App\Http\Controllers;

use App\Models\Administrateur;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Dependency injection of TokenService for token management
    public function __construct(
        protected TokenService $tokenService,
    ) {
        $this->tokenService = $tokenService;
    }

    /**
     * Register an admin into the database.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $admin = new Administrateur;

        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:administrateurs',
                'password' => 'required|min:6',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'La validation des champs a échoué.',
            ], 422);
        }

        $admin = Administrateur::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $this->tokenService->createNewToken($admin);

        return response()->json([
            'email' => $admin->email,
            'access_token' => $token->plainTextToken
        ], 200);
    }

    /**
     * Authenticate an admin and grants authorization to use the private API endpoints.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        try {
            $validated = $request->validate([
                'email' => 'required|email|exists:administrateurs',
                'password' => 'required',
            ]);

            $admin = Administrateur::where('email', $request['email'])->first();

            if($admin) {
                Hash::check($validated['password'], $admin->password);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'L\'authentification a échoué.',
            ], 422);
        }

        $accessToken = $this->tokenService->findOrUpdateToken($admin);

        return response()->json([
            'email' => $admin->email,
            'access_token' => $accessToken->plainTextToken
        ], 200);
    }
}
