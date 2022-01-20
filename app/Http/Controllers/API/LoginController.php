<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'name' => 'required|string',
                'password' => 'required|string'
            ]);
            if (Auth::attempt($credentials) === true) {
                $user = User::where('name', $credentials['name'])->first();
                if (Hash::check($credentials['password'], $user->password) === false) {
                    throw new Exception('Password error');
                }
                $user->tokens()->delete();
                $token = $user->createToken("login:user{$user->id}")->plainTextToken;
                return response()->json(['token' => $token ], Response::HTTP_OK);
            }
        } catch(ValidationException $e) {
            return response()->json('Validation error', Response::HTTP_BAD_REQUEST);
        } catch(Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        return response()->json('User Not Found.', Response::HTTP_NOT_FOUND);
    }
}
