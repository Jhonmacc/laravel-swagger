<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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

// Rota para autenticar o usuário e gerar o token
// Route::post('/login', function (Request $request) {

//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);
//     if (Auth::attempt($credentials)) {
//         $user = Auth::user();
//         $token = $user->createToken('swagger-token')->plainTextToken;

//         return response()->json([
//             'token' => $token,
//         ]);
//     }

//    return response()->json(['message' => 'Unauthorized'], 401);

//
//    $user = User::where('email', $request->email)->first();

//
//    if (!$user || !Hash::check($request->password, $user->password)) {
//        return response()->json(['message' => 'Credenciais inválidas'], 401);
//    }
//
//     $token = $user->createToken('api-token')->plainTextToken;


//     return response()->json(['token' => $token]);
// });
//
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Grupo de rotas protegidas para CRUD de funcionários
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('funcionarios', FuncionarioController::class);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });
