<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rota para buscar todos os usuários
Route::get('/read_users', [UserController::class, 'index']);

// Adicionar rotas para buscar por nome e telefone 
Route::post('/search_by_name', [UserController::class, 'searchByName']);
Route::post('/search_by_phone', [UserController::class, 'searchByPhone']);

//Rota para inserção de usuários
Route::post('/insert_users', [UserController::class, 'store']);

// Adicionar rota para atualizar usuários 
Route::put('/update_user_by_phone/{telefone}', [UserController::class, 'updateByPhone']);


// Adicionar rota para deletar usuários 
Route::delete('/delete_user/{id}', [UserController::class, 'destroy']);

// Adicionar rota para consultar CEP 
Route::get('/via_cep/{cep}', [UserController::class, 'getCepDetails']);