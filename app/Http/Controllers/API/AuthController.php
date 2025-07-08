<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Handle user login.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Login logic here
        // Validate the request, authenticate the user, and return a response
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Handle user registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        // Registration logic here
        // Validate the request, create a new user, and return a response

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data['password'] = bcrypt($data['password']);
        $user = \App\Models\User::create($data);

        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }


    /**
     * Handle user logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // Logout logic here

        try {
            auth()->logout();
            return response()->json(['message' => 'Logout successful']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function users()
    {
        // Logic to retrieve users
        try {
            $users = User::all();
            if ($users->isEmpty()) {
                return response()->json(['message' => 'No users found'], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving users',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
