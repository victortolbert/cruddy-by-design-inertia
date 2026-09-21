<?php

namespace App\Http\Requests;

use App\Models\Episode;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlaybackProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        $episode = $this->route('episode');

        return $episode instanceof Episode
            && ($this->user()?->can('view', $episode) ?? false);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'position' => ['required', 'integer', 'min:0'],
            'completed' => ['sometimes', 'boolean'],
        ];
    }
}
