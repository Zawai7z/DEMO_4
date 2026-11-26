<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'genres' => $this->genres,
            'score' => $this->score,
            'synopsis' => $this->synopsis,
            'episodes' => $this->episodes,
            'status' => $this->status,
            'season' => $this->season,
            'duration_minutes' => $this->duration_minutes,
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}

