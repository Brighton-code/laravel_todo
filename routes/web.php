<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Models\Todo;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/todos', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/todos', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/todos/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::get('/todos/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::delete('/todos/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/todos/{category}', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos/{category}', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{category}/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{category}/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
});

//Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'todos'], function () {
//    Route::resource('', CategoryController::class)
//        ->only(['index', 'store', 'update', 'destroy']);
//    Route::resource('{category}', TodoController::class)
//        ->only('index', 'store', 'update', 'destroy');
//});


require __DIR__.'/auth.php';
