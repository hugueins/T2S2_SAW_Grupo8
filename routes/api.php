<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Hash;

// Ruta pública: Registro
Route::group([
    "prefix"=>"v1", 
    "namespace" => "App\Http\Controller\Api",
], function (){
    Route::get("healthy", function(){
        return ["status" => true, "data" => "Estado server activo"];
    });
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

// Rutas protegidas por Sanctum
Route::group([
    "prefix"=>"v1", 
    "namespace" => "App\Http\Controller\Api",
    "middleware" => 'auth:sanctum'
],function () {
    Route::get('/autorizacion', [UserController::class, 'auth']);
    Route::get('/users', [UserController::class, 'getAll']);
});