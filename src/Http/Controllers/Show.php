<?php

namespace LaravelEnso\Avatars\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use LaravelEnso\Avatars\Models\Avatar;
use LaravelEnso\Avatars\Services\Generators\Laravolt;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Show extends Controller
{
    public function __invoke(Avatar $avatar): HttpResponse
    {
        if ($avatar->url) {
            return Redirect::to($avatar->url);
        }

        return $avatar->file_id
            ? $avatar->file->inline()
            : Response::make((new Laravolt($avatar))->svg(), 200, [
                'Content-Type' => 'image/svg+xml',
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ]);
    }
}
