<?php

namespace LaravelEnso\Avatars\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Avatar extends JsonResource
{
    public function toArray($request)
    {
        return ['id' => $this->id];
    }
}
