<?php

use App\Http\Controllers\ClienteController;
<<<<<<< HEAD
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ReporteController;
=======
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyectoController;
>>>>>>> 88d4633176a6c74f1edbc51bdeb0715a1cfedb93
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

<<<<<<< HEAD
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
=======
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
>>>>>>> 88d4633176a6c74f1edbc51bdeb0715a1cfedb93

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('clientes',  ClienteController::class);
    Route::resource('proyectos', ProyectoController::class);
    Route::resource('tareas',    TareaController::class);

<<<<<<< HEAD
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/',          [ReporteController::class, 'index'])->name('index');
        Route::get('/clientes',  [ReporteController::class, 'clientes'])->name('clientes');
        Route::get('/proyectos', [ReporteController::class, 'proyectos'])->name('proyectos');
        Route::get('/tareas',    [ReporteController::class, 'tareas'])->name('tareas');
        Route::get('/{reporte}/descargar', [ReporteController::class, 'descargar'])->name('descargar');
    });

=======
>>>>>>> 88d4633176a6c74f1edbc51bdeb0715a1cfedb93
});

require __DIR__.'/auth.php';
