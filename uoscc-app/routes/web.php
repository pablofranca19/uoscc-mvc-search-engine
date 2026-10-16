<?php

use App\Http\Controllers\OscController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('oscs.index');
});

Route::resource('oscs', OscController::class)->except('show');
