<?php

use Illuminate\Support\Facades\Route;

// ⛔ letakkan paling bawah
Route::get('/{any}', function () {
    return file_get_contents(public_path('bangkit-cell/index.html'));
})->where('any', '^(?!api|react/assets|react/.*\.(js|css|png|jpg|svg|ico)).*$');

