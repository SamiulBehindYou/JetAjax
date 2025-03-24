<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('task', [TaskController::class, 'task'])->name('task');
Route::post('task', [TaskController::class, 'task_store'])->name('task.store');
Route::get('gettask', [TaskController::class, 'gettask'])->name('gettask');
Route::get('make-done/{id}', [TaskController::class, 'make_done'])->name('makedone');
Route::get('accept/{id}', [TaskController::class, 'accept'])->name('accept');
Route::get('reject/{id}', [TaskController::class, 'reject'])->name('reject');
Route::get('delete-task/{id}', [TaskController::class, 'deltetask'])->name('deltetask');



Route::get('post', function(){
    return view('post');
})->name('post');
