<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\ClassRoom;

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

Route::get('/classes/{class}/students', function($classId) {
    return ClassRoom::findOrFail($classId)
        ->students()
        ->select('id', 'first_name', 'last_name', 'student_number', 'passport_photo')
        ->get();
});