<?php

namespace App\Http\Requests;

use App\Models\Podcast;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePodcastCoverImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $podcast = $this->route('podcast');

        return $podcast instanceof Podcast
            && ($this->user()?->can('update', $podcast) ?? false);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'cover' => ['required', 'image', 'max:2048', Rule::dimensions()->minWidth(500)->minHeight(500)],
        ];
    }
}
