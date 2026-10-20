<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\SpaceReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/apoiadores', 'apoiadores')->name('apoiadores');

Route::get('/eventos', [ActivityController::class, 'index'])->name('eventos');
Route::post('/eventos', [ActivityController::class, 'store'])->name('eventos.store');
Route::get('/eventos/{id}', [ActivityController::class, 'show'])
    ->whereNumber('id')
    ->name('eventos.show');
Route::post('/eventos/{id}/inscricao', [RegistrationController::class, 'store'])
    ->whereNumber('id')
    ->name('eventos.inscricao');

Route::get('/organizacoes', [OrganizationController::class, 'index'])->name('organizacoes');
Route::post('/organizacoes', [OrganizationController::class, 'store'])->name('organizacoes.store');
Route::get('/organizacoes/{id}', [OrganizationController::class, 'show'])
    ->whereNumber('id')
    ->name('organizacoes.show');

Route::get('/espacos', [SpaceController::class, 'index'])->name('espacos');
Route::post('/espacos', [SpaceController::class, 'store'])->name('espacos.store');
Route::get('/espacos/{id}', [SpaceController::class, 'show'])
    ->whereNumber('id')
    ->name('espacos.show');
Route::post('/espacos/{id}/reserva', [SpaceReservationController::class, 'store'])
    ->whereNumber('id')
    ->name('espacos.reserva');

Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
Route::get('/usuarios/{id}', [UserController::class, 'show'])
    ->whereNumber('id')
    ->name('users.show');
