<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'core'])
    ->namespace('LaravelEnso\Avatars\Http\Controllers')
    ->prefix('api/core/avatars')
    ->as('core.avatars.')
    ->group(function () {
        Route::post('', 'Store')->name('store');
        Route::patch('{avatar}', 'Update')->name('update');
        Route::get('{avatar}', 'Show')->name('show');
    });
