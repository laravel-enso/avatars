<?php

Route::namespace('LaravelEnso\AvatarManager\app\Http\Controllers')
	->prefix('api')
    ->group(function () {
        Route::prefix('core')->as('core.')
            ->group(function () {
                Route::resource('avatars', 'AvatarController', ['only' => ['show', 'store', 'destroy']]);
            });
    });
