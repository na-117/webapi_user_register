<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models as Models;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function add(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string',
            ]);
            $result = Models\User::create(['name'=> $request->input('name')]);
        } catch(ValidationException $e) {
            return response()->json([
                'result' => false,
                'error' => [
                    'messages' => 'Validation error'
                ],
            ], Response::HTTP_BAD_REQUEST);
        } catch(Exception $e) {
            return response()->json([
                'result' => false,
                'error' => [
                    'messages' => 'error!'
                ],
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
        
        return response()->json($result, Response::HTTP_CREATED);
    }

    public function fetchAll() {
        try {
            $result = Models\User::all();
        } catch(Exception $e) {
            return response()->json([
                'result' => false,
                'error' => [
                    'messages' => $e->getMessage()
                ],
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
        return response()->json($result);
    }

    public function fetchById($id) {
        try {
            $validator = Validator::make(['id' => $id],[
                'id' => 'required|int|min:1'
            ]);
            $validator->validate();
            $result = Models\User::where('id', $id)
                    ->first();
            if (isset($result) === false) {
                throw new Exception('user not found');
            }
        } catch(ValidationException $e) {
            return response()->json([
                'result' => false,
                'error' => [
                    'messages' => 'Validation error'
                ],
            ], Response::HTTP_BAD_REQUEST);
        } catch(QueryException $e) {
            return response()->json([
                'result' => false,
                'error' => [
                    'messages' => $e->getMessage()
                ],
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        } catch(Exception $e) {
            return response()->json([
                'result' => false,
                'error' => [
                    'messages' => $e->getMessage()
                ],
            ], Response::HTTP_NOT_FOUND);
        }
        
        return response()->json($result);
    }
}
