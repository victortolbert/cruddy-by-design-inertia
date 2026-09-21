<?php

namespace App\Http\Resources;

use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Episode
 */
class EpisodeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'show_notes' => $this->show_notes,
            'audio_url' => $this->audio_url,
            'duration_seconds' => $this->duration_seconds,
            'duration_for_humans' => $this->durationForHumans(),
            'is_published' => $this->isPublished(),
            'published_at' => $this->published_at?->toDateString(),
            'published_at_for_humans' => $this->published_at?->format('M j, Y'),
            'podcast' => PodcastResource::make($this->whenLoaded('podcast')),
        ];
    }
}
