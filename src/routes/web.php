<?php

Route::group([
    'namespace'  => 'LaravelEnso\AvatarManager\app\Http\Controllers',
    'middleware' => ['web', 'auth', 'core'],
], function () {
    Route::group(['prefix' => 'core', 'as' => 'core.'], function () {
        Route::resource('avatars', 'AvatarController');
    });
});
