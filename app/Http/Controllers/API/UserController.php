<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models as Models;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function add(Request $request) {
        try {
            $result = Models\User::create(['name'=> $request->input('name')]);
        } catch(\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()]
                ],
            ];
            return $result->tojson();
        }
        return $result->tojson();
    }

    public function fetchAll() {
        try {
            $result = Models\User::all();
        } catch(\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()]
                ],
            ];
            return $result->tojson();
        }
        return $result->tojson();
    }

    public function fetchById($id) {
        try {
            $result = Models\User::where('id', $id)
                    ->get();
        } catch(\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()]
                ],
            ];
            return $result->tojson();
        }
        return $result->tojson();
    }
}
