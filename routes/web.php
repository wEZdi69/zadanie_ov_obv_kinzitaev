<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\SubmissionWebController;
use App\Http\Controllers\Web\AttachmentWebController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/login/{role}', [HomeController::class, 'login'])->name('login.quick');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/submissions/create', [SubmissionWebController::class, 'create'])->name('submission.create');
    Route::post('/submissions', [SubmissionWebController::class, 'store'])->name('submission.store');
    Route::get('/submissions/{submission}', [SubmissionWebController::class, 'show'])->name('submission.show');
    Route::get('/submissions/{submission}/edit', [SubmissionWebController::class, 'edit'])->name('submission.edit');
    Route::put('/submissions/{submission}', [SubmissionWebController::class, 'update'])->name('submission.update');
    Route::post('/submissions/{submission}/submit', [SubmissionWebController::class, 'submit'])->name('submission.submit');
    Route::post('/submissions/{submission}/status', [SubmissionWebController::class, 'changeStatus'])->name('submission.status');
    Route::post('/submissions/{submission}/comments', [SubmissionWebController::class, 'addComment'])->name('submission.comment');
    
    Route::post('/submissions/{submission}/attachments', [AttachmentWebController::class, 'upload'])->name('attachment.upload');
    Route::get('/attachments/{attachment}/download', [AttachmentWebController::class, 'download'])->name('attachment.download');
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/contests', [AdminController::class, 'contests'])->name('contests');
        Route::post('/contests', [AdminController::class, 'createContest'])->name('contests.create');
        Route::put('/contests/{contest}', [AdminController::class, 'updateContest'])->name('contests.update');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    });
});