<?php

use Illuminate\Support\Facades\Route;
use Modules\Files\Http\Controllers\FilesController;

Route::middleware('web')
    ->prefix('files')
    ->group(function () {
        Route::get('/', [FilesController::class, 'index']);
    });