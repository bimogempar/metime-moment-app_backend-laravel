<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registerAuth(Request $request)
    {
        if ($request->user()->role === 1) {
            try {
                $attr = $request->validate([
                    'name' => 'required|max:20',
                    'email' => 'required|email',
                    'username' => 'required',
                    'password' => 'required',
                ]);

                $attr['password'] = Hash::make($request->password);
                $createUser = User::create($attr);

                return response()->json([
                    'access_token' => $createUser->createToken($createUser)->plainTextToken,
                    'user' => $createUser
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
        return response()->json(['message' => 'Not for this role']);
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'email',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->orWhere('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken($user)->plainTextToken,
            'message' => 'successfully',
            'user_logged_in' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $response = [
            'status' => 'success',
            'msg' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($response, 200);
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }
}
