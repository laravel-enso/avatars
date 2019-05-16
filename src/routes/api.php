<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Avatars\app\Http\Controllers')
    ->prefix('api/core/avatars')
    ->as('core.avatars.')
    ->group(function () {
        Route::post('', 'Store')->name('store');
        Route::patch('{avatar}', 'Update')->name('update');
        Route::get('{avatar}', 'Show')->name('show');
    });
