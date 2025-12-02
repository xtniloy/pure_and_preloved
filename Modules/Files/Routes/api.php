<?php

use Illuminate\Support\Facades\Route;
use Modules\Files\Http\Controllers\FilesController;

Route::middleware('api')
    ->prefix('api/files')
    ->group(function () {
        Route::get('/', [FilesController::class, 'index']);
    });