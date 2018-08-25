<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\AvatarManager\app\Http\Controllers')
    ->prefix('api/core')->as('core.')
    ->group(function () {
        Route::resource('avatars', 'AvatarController', [
            'only' => ['show', 'store', 'update'],
        ]);
    });
