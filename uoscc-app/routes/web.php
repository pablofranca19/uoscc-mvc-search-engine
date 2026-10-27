<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationProfileController;
use App\Http\Controllers\OrganizationRegistrationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\SpaceReservationController;
use App\Http\Controllers\SupporterAuthController;
use App\Http\Controllers\SupporterDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/apoiadores/cadastro', [SupporterAuthController::class, 'create'])->name('apoiadores.cadastro');
    Route::post('/apoiadores/cadastro', [SupporterAuthController::class, 'store'])->name('apoiadores.cadastro.store');
    Route::get('/apoiadores/entrar', [SupporterAuthController::class, 'login'])->name('login');
    Route::post('/apoiadores/entrar', [SupporterAuthController::class, 'authenticate'])->name('apoiadores.login');
    Route::get('/organizacoes/cadastro', [OrganizationRegistrationController::class, 'create'])->name('organizacoes.cadastro');
    Route::post('/organizacoes/cadastro', [OrganizationRegistrationController::class, 'store'])->name('organizacoes.cadastro.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/organizacoes/perfil', [OrganizationProfileController::class, 'show'])->name('organizacoes.perfil');
    Route::get('/espacos/cadastro', [SpaceController::class, 'create'])->name('espacos.create');

    Route::get('/espacos/{id}/reservas', [SpaceReservationController::class, 'show'])
        ->whereNumber('id')
        ->name('espacos.reservas');
    Route::post('/espacos/{id}/reservas/{reservation}/aprovar', [SpaceReservationController::class, 'approve'])
        ->whereNumber(['id', 'reservation'])
        ->name('espacos.reservas.aprovar');
    Route::post('/espacos/{id}/reservas/{reservation}/recusar', [SpaceReservationController::class, 'reject'])
        ->whereNumber(['id', 'reservation'])
        ->name('espacos.reservas.recusar');

    Route::get('/usuarios/{id}', [UserController::class, 'show'])
        ->whereNumber('id')
        ->name('users.show');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');

    Route::get('/apoiadores', [SupporterDashboardController::class, 'index'])->name('apoiadores');
    Route::get('/apoiadores/reservas', [SupporterDashboardController::class, 'reservations'])->name('apoiadores.reservas');
    Route::put('/apoiadores/perfil', [SupporterDashboardController::class, 'updateProfile'])->name('apoiadores.perfil');
    Route::post('/apoiadores/favoritos/{type}/{id}', [SupporterDashboardController::class, 'toggleFavorite'])->name('apoiadores.favoritos');
    Route::post('/apoiadores/inscricoes/{type}/{id}', [SupporterDashboardController::class, 'register'])->name('apoiadores.inscricoes');
    Route::post('/apoiadores/participacoes/{type}/{id}/confirmar', [SupporterDashboardController::class, 'confirmParticipation'])->name('apoiadores.participacoes.confirmar');
    Route::get('/apoiadores/certificados/{type}/{id}', [SupporterDashboardController::class, 'certificate'])->name('apoiadores.certificados');
    Route::post('/apoiadores/sair', [SupporterAuthController::class, 'destroy'])->name('apoiadores.sair');

    Route::get('/eventos/cadastro', [ActivityController::class, 'create'])->name('eventos.create');
});

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
