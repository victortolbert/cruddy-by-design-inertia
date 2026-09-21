<?php

namespace App\Http\Requests;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Foundation\Http\FormRequest;

class EpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $episode = $this->route('episode');
        $podcast = $this->route('podcast');

        if ($episode instanceof Episode) {
            return $this->user()?->can('update', $episode) ?? false;
        }

        return $podcast instanceof Podcast
            && ($this->user()?->can('update', $podcast) ?? false);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'show_notes' => ['nullable', 'string', 'max:10000'],
            'audio_url' => ['required', 'url', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],
        ];
    }

    /**
     * Validated input mapped onto episode columns.
     *
     * @return array<string, string|int|null>
     */
    public function episodeAttributes(): array
    {
        $minutes = $this->validated('duration_minutes');

        return [
            'title' => $this->validated('title'),
            'description' => $this->validated('description') ?: null,
            'show_notes' => $this->validated('show_notes') ?: null,
            'audio_url' => $this->validated('audio_url'),
            'duration_seconds' => $minutes !== null ? (int) $minutes * 60 : null,
        ];
    }
}
