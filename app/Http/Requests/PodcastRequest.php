<?php

namespace App\Http\Requests;

use App\Models\Podcast;
use Illuminate\Foundation\Http\FormRequest;

class PodcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        $podcast = $this->route('podcast');

        return $podcast instanceof Podcast
            ? $this->user()?->can('update', $podcast) ?? false
            : $this->user()?->can('create', Podcast::class) ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'author' => ['nullable', 'string', 'max:150'],
            'website' => ['nullable', 'url', 'max:255'],
            'feed_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
