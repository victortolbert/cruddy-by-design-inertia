<?php

namespace App\Http\Resources;

use App\Models\PlaybackProgress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PlaybackProgress
 */
class PlaybackProgressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'position_seconds' => $this->position_seconds,
            'is_completed' => $this->isCompleted(),
            'percent_complete' => $this->percentComplete(),
            'episode' => EpisodeResource::make($this->whenLoaded('episode')),
        ];
    }
}
