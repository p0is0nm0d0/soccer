<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              required={"email"},
     *              @OA\Property(
     *                 property="email", 
     *                 type="string", 
     *                 format="email", 
     *                 example="admin@example.com"
     *              ),
     *              @OA\Property(
     *                  property="password", 
     *                  type="string",
     *                  format="password", 
     *                  example="123456"
     *              ),
     *          )
     *    ),  
     *     
     *    @OA\Response(
     *         response="200",
     *         description="Successful",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Login successful"
     *              ),
     *              @OA\Property(
     *                  property="token", 
     *                  type="string", 
     *                  example="3|buTy2CqoliUIEadns8FnWtLfh12WahMfIwShipIi9ca6c4e4"
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="401"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Invalid credentials"
     *              ),
     *          ), 
     *     ), 
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'statuscode' => '401',
                    'message' => 'Invalid credentials'
                ], 401
            );
        }

        // Create token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'statuscide' => 200,
            'message' => 'Login successful',
            'token' => $token,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout",     
     *    @OA\Response(
     *         response="200",
     *         description="Successful",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Logout successful"
     *              ),
     *          ), 
     *     ),
     * )
     */
    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        return response()->json([
                'statuscode' => 200, 
                'message' => 'Logout successful'
            ], 200
        );
    }

}
