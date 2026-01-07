<?php

use Illuminate\Support\Facades\Route;
use Modules\Files\Http\Controllers\FilesController;

Route::middleware(['web', 'auth:admin'])->group(function () {})
    ->prefix('admin/files')
    ->group(function () {
        Route::get('/', [FilesController::class, 'index'])->name('admin.file.index');
        Route::post('/upload-chunk', [FilesController::class, 'uploadChunk'])
            ->name('admin.file.upload.chunk');
        Route::post('/upload-thumbnail', [FilesController::class, 'uploadThumbnail'])
            ->name('admin.file.upload.thumbnail');
        Route::get('/download/{fileId?}', [FilesController::class, 'download'])
            ->name('admin.file.download');
        Route::delete('/delete/{fileId?}', [FilesController::class, 'delete'])
            ->name('admin.file.delete');

        Route::get('/view/{fileId?}', [FilesController::class, 'view'])
            ->name('admin.file.view');
    });

Route::prefix('files')
    ->group(function () {
        Route::get('/uploaded_asset/{stored_name}', [FilesController::class, 'uploaded_asset'])
            ->name('admin.file.uploaded_asset');
        Route::get('/thumbnail/{fileId}', [FilesController::class, 'thumbnail'])
            ->name('admin.file.thumbnail');
    });


/*

Note:
need to move upload-chunk route out of web guard when auth milldleware used
web guared has dependecy with csrf it can be an issue when large file upload


*/
