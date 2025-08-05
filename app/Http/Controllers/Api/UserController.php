<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getAll(Request $request)
    {   
        $user = User::all();
        return response()->json([
            'message' => "",
            'data' => $user,
            'status' => true
        ], 200);
    }

    public function register(Request $request)
    {   
        $rs = User::where("email",$request->input("email") )->first();
        $message = "";
        $status = false;
        $data = [];
        
        if ($rs){
            $message = "Usuario ya se encuentra registrado";
            $status = false;
        }else{
            $record = [
                'name' => $request->input("name"),
                'email' => $request->input("email"),
                'password' => Hash::make($request->input("password")),
                'role' => $request->input("role"),
            ];
            error_log(json_encode($record)); // Sirve para visualizar datos en consola
    
            $user = User::create($record);

            $message = "Usuario registrado exitosamente";
            $data = $user;
            $status = true;
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], 201);
    }

    public function login(Request $request)
    {   
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $message = "";
        $status = false;
        $data = [];
        
        $user = User::where("email",$request->input("email") )->first();
        if (!$user){
            $message = "Usuario no se encuentra registrado";
            $status = false;
        }else{
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                     'message' => 'Credenciales inválidas',
                    'data' => [],
                    'status' => false
                ], 401);
            } else{

            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $message = "Usuario autenticado y autorizado";
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ];
            $status = true;
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], 200);
    }

    public function auth(Request $request)
    {   
        $message = "";
        $status = false;
        $data = [];
        
        $data = $request->input("tema");

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status
        ], 200);
    }
}