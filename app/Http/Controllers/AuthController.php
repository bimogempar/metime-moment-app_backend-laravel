<?php

namespace App\Http\Controllers;

use App\Mail\TestingMail;
use App\Models\TokenInitialPassword;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registerAuth(Request $request)
    {
        if ($request->user()->role === 2) {
            try {
                $attr = $request->validate([
                    'name' => 'required|max:20',
                    'email' => 'required|email',
                    'username' => 'required',
                ]);

                $tokeninitialpassword = Str::random(30);

                $user = new User();
                $user->name = $attr['name'];
                $user->email = $attr['email'];
                $user->username = $attr['username'];
                $user->no_hp = 0;
                $user->password = Hash::make('password');
                $user->save();

                $user->TokenInitialPassword()->save(
                    new TokenInitialPassword([
                        'token_initial_password' => $tokeninitialpassword,
                        'status' => 0,
                    ])
                );

                $user->sendEmailRegister($user, $tokeninitialpassword);

                return response()->json([
                    'token_initial_password' => $tokeninitialpassword,
                    'user' => $user,
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ]);
            }
        }
        return response()->json(['message' => 'Not for this role']);
    }

    public function formSetPass($token_initial_password)
    {
        try {
            $tokeninitialpassword = TokenInitialPassword::where('token_initial_password', $token_initial_password)->firstOrFail();
            if ($tokeninitialpassword) {
                if ($user = User::where('id', $tokeninitialpassword->user_id)->first()) {
                    return $user;
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function setPass(Request $request)
    {
        try {
            $attr = $request->validate([
                'token_initial_password' => 'required',
                'password' => 'required|min:8',
            ]);

            $request_token = $request->token_initial_password;
            $tokeninitialpassword = TokenInitialPassword::where('token_initial_password', $request_token)->firstOrFail();

            if ($tokeninitialpassword) {
                if ($user = User::where('id', $tokeninitialpassword->user_id)->first()) {
                    $user->password = Hash::make($attr['password']);
                    $user->save();
                    $tokeninitialpassword->status = 1;
                    $tokeninitialpassword->save();
                    return response()->json([
                        'message' => 'Successfully set password',
                    ], 200);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function loginAuth(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'email' => 'email',
                'password' => 'required',
            ]);

            $user = User::where('username', $request->username)->orWhere('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'error' => 'The provided credentials are incorrect.',
                ]);
            }

            $accesstoken = $user->createToken($user)->plainTextToken;

            if ($user->TokenInitialPassword->status === 0) {
                return response()->json([
                    'error' => 'Please check your email to set your password',
                ]);
            }

            return response()->json([
                'access_token' => $accesstoken,
                'message' => 'successfully',
                'user_logged_in' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'The provided credentials are incorrect.',
            ]);
        }
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
