<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\AvatarManager\app\Http\Controllers')
    ->group(function () {
        Route::prefix('core')->as('core.')
            ->group(function () {
                Route::resource('avatars', 'AvatarController', ['only' => ['show', 'store', 'destroy']]);
            });
    });
