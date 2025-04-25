<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\halo\HaloController;
use App\Http\Controllers\Todo\TodoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halo',[HaloController::class, 'index']);

Route::get('/todo', [TodoController::class, 'index'])->name('todo');

Route::post('/todo', [TodoController::class, 'store'])->name('todo.post');

Route::delete('/todo/{id}', [TodoController::class, 'destroy'])->name('todo.delete');

Route::put('/todo/{id}', [TodoController::class, 'update'])->name('todo.update');