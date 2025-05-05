<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\halo\HaloController;
use App\Http\Controllers\Todo\TodoController;
use App\Http\Controllers\User\ForgotPasswordController;
use App\Http\Controllers\User\UserController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function(){
    Route::get('/halo',[HaloController::class, 'index']);
    Route::get('/todo', [TodoController::class, 'index'])->name('todo');
    Route::post('/todo', [TodoController::class, 'store'])->name('todo.post');
    Route::delete('/todo/{id}', [TodoController::class, 'destroy'])->name('todo.delete');
    Route::put('/todo/{id}', [TodoController::class, 'update'])->name('todo.update');
    Route::get('/user/update-data', [UserController::class, 'updateData'])->name('user.update-data');
    Route::post('/user/update-data', [UserController::class, 'doUpdateData'])->name('user.update-data.post');
});

Route::middleware('guest')->group(function(){
    Route::get('/user/login',[UserController::class, 'login'])->name('login');
    Route::post('/user/login',[UserController::class, 'doLogin'])->name('login.post');
    Route::get('/user/register', [UserController::class, 'register'])->name('register');
    Route::post('/user/register', [UserController::class, 'doRegister'])->name('register.post');
    Route::get('/user/verifyaccount/{token}', [UserController::class, 'verifyAccount'])->name('user.verifyaccount');
    Route::get('/user/forgot-password', [ForgotPasswordController::class, 'forgotPasswordForm'])->name('forgot-password');
    Route::post('/user/forgot-password', [ForgotPasswordController::class, 'doforgotPasswordForm'])->name('forgot-password.post');
    Route::get('/user/reset-password/{token}', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password');
    Route::post('/user/reset-password', [ForgotPasswordController::class, 'doResetPassword'])->name('reset-password.post');
});
Route::get('/user/logout',[UserController::class, 'logout'])->name('user.logout');

