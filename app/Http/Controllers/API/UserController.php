<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models as Models;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class UserController extends Controller
{

    public function add(Request $request) {
        try {
            $result = Models\User::create(['name'=> $request->input('name')]);
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
            $result = Models\User::where('id', $id)
                    ->first();
            if (isset($result) === false) {
                throw new Exception('not_exit_data');
            }
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
