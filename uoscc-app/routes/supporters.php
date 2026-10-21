<?php

use App\Http\Controllers\SupporterApplicationController;
use App\Http\Controllers\SupporterAuthController;
use App\Http\Controllers\SupporterPortalController;
use App\Http\Controllers\SupporterReviewController;
use App\Http\Middleware\EnsureApprovedSupporter;
use App\Http\Middleware\PreventSupporterCaching;
use Illuminate\Support\Facades\Route;

Route::prefix('apoiadores')->name('apoiadores.')->middleware(PreventSupporterCaching::class)->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/entrar', [SupporterAuthController::class, 'create'])->name('login');
        Route::post('/entrar', [SupporterAuthController::class, 'store'])->middleware('throttle:supporter-login')->name('login.store');
        Route::get('/cadastro', [SupporterAuthController::class, 'registerForm'])->name('cadastro');
        Route::post('/cadastro', [SupporterAuthController::class, 'register'])->middleware('throttle:10,1')->name('cadastro.store');
    });
    Route::middleware('auth')->group(function (): void {
        Route::get('/acesso', [SupporterAuthController::class, 'access'])->name('acesso');
        Route::post('/sair', [SupporterAuthController::class, 'destroy'])->name('logout');
        Route::get('/solicitacao', [SupporterApplicationController::class, 'show'])->name('solicitacao');
        Route::post('/solicitacao', [SupporterApplicationController::class, 'store'])->middleware('throttle:10,1')->name('solicitacao.store');
        Route::middleware('can:review-supporters')->group(function (): void {
            Route::get('/equipe', [SupporterReviewController::class, 'index'])->name('admin');
            Route::patch('/equipe/{application}', [SupporterReviewController::class, 'update'])->name('admin.review');
        });
        Route::middleware(EnsureApprovedSupporter::class)->group(function (): void {
            Route::get('/painel', [SupporterPortalController::class, 'index'])->name('painel');
            Route::get('/oportunidades', [SupporterPortalController::class, 'opportunities'])->name('oportunidades');
            Route::get('/perfil', [SupporterPortalController::class, 'profile'])->name('perfil');
            Route::patch('/perfil', [SupporterPortalController::class, 'updateProfile'])->name('perfil.update');
        });
    });
});
