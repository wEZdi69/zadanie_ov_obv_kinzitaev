<?php

use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;

// Публичные маршруты (для тестирования)
Route::post('/login', function () {
    // Простой логин для тестирования
    $credentials = request()->only('email', 'password');
    
    if (auth()->attempt($credentials)) {
        return response()->json(['user' => auth()->user()]);
    }
    
    return response()->json(['error' => 'Invalid credentials'], 401);
});

// Защищенные маршруты
Route::middleware('auth:sanctum')->group(function () {
    // Submissions
    Route::apiResource('submissions', SubmissionController::class)->except(['destroy']);
    Route::post('/submissions/{submission}/submit', [SubmissionController::class, 'submit']);
    Route::post('/submissions/{submission}/change-status', [SubmissionController::class, 'changeStatus']);
    Route->post('/submissions/{submission}/comments', [SubmissionController::class, 'addComment']);
    
    // Attachments
    Route::post('/submissions/{submission}/attachments', [AttachmentController::class, 'upload']);
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download']);
});