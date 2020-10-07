<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Avatars\Http\Controllers\Store;
use LaravelEnso\Avatars\Http\Controllers\Update;
use LaravelEnso\Avatars\Http\Controllers\Show;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/core/avatars')
    ->as('core.avatars.')
    ->group(function () {
        Route::post('', Store::class)->name('store');
        Route::patch('{avatar}', Update::class)->name('update');
        Route::get('{avatar}', Show::class)->name('show');
    });
