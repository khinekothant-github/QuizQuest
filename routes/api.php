<?php

use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');

// Public routes
Route::get('/home', function () {
    return response()->json(['message' => 'Welcome to the Quiz Quest!']);
});
Route::get('/about', function () {
    return response()->json(['message' => 'About the Quiz Quest!']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/quizzes', [QuizController::class, 'getQuizzes'])->middleware('permission:view quizzes');
    Route::post('/quizzes/submit', [QuizController::class, 'submit'])->middleware('permission:submit quizzes');
    Route::get('/leaderboard', [LeaderBoardController::class, 'index'])->middleware('permission:view leaderboard');
    Route::post('logout', [AuthController::class, 'logout']);
    // Permissions management routes
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:view roles');
});

// New route to fetch user information
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);

// User management routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:view user');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:create user');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('permission:view user');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:edit user');
    Route::put('/users/{id}/assign-role', [UserController::class, 'assignRole'])->middleware('permission:assign role');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('permission:delete user');
    Route::get('/roles', [UserController::class, 'availableRoles'])->middleware('permission:view roles');
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:view roles');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:create role');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('permission:edit role');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('permission:delete role');
    Route::post('/roles/{id}/permissions', [RoleController::class, 'assignPermissions'])->middleware('permission:assign permissions');
});

Route::middleware('auth:sanctum')->get('permissions/check', [AuthController::class, 'checkPermission']);

Route::middleware('auth:sanctum')->get('/user-details', [UserController::class, 'getUserDetails']);
