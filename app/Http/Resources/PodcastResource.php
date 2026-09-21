<?php

namespace App\Http\Resources;

use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Podcast
 */
class PodcastResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'author' => $this->author,
            'website' => $this->website,
            'website_host' => $this->websiteHost(),
            'feed_url' => $this->feed_url,
            'cover_image_url' => $this->coverImageUrl(),
            'is_owner' => $user !== null && $this->isOwnedBy($user),
        ];
    }
}
