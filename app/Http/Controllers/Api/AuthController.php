<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *     title="Laravel Procurement API",
 *     version="1.0.0"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User login",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *             required={"email","password"},
     *
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="access_token", type="string"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Cek apakah email ada di database
        $user = User::where('email', $credentials['email'])->first();
        if (! $user) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        // Cek apakah password cocok
        if (! auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid password'], 401);
        }

        // Jika email dan password valid, buat token
        $token = auth('api')->attempt($credentials);

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register new user",
     *     tags={"Authentication"},
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Logout user",
     *     tags={"Authentication"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out"
     *     )
     * )
     */
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
        ]);
    }
}
