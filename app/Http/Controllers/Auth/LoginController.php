<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginController extends Controller
{
    public function register(StoreUserRequest $request)
    {   # As an admin, I can register myself to the portal (name, email, and password)
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $data = [
                'user_id' => null,
                'contact_no' => '',
                'birthday' => date("Y-m-d", strtotime("-".rand(20, 30) ." years")),
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'password' => Hash::make($validated['password']),
                'email' => $validated['email'],
                'role_id' => Role::ROLE_ADMIN
            ];

            $user = User::create($data);

            DB::commit();

            return response()->json(['message' => 'Admin added successfully', 'user' => $user], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    { # As an admin, I can log in using my email and password
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}