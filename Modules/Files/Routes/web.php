<?php

use Illuminate\Support\Facades\Route;
use Modules\Files\Http\Controllers\FilesController;

Route::middleware('web')
    ->prefix('files')
    ->group(function () {
        Route::get('/', [FilesController::class, 'index']);
        Route::post('/upload-chunk', [FilesController::class, 'uploadChunk'])
            ->name('admin.file.upload.chunk');
        Route::get('/download/{fileId?}', [FilesController::class, 'download'])
            ->name('admin.file.download');
        Route::delete('/delete/{fileId?}', [FilesController::class, 'delete'])
            ->name('admin.file.delete');

        Route::get('/view/{fileId?}', [FilesController::class, 'view'])
            ->name('admin.file.view');
    });
