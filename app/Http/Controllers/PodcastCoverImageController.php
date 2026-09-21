<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePodcastCoverImageRequest;
use App\Models\Podcast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

/**
 * Tip 2: a property edited on its own is its own resource. The cover image
 * has `update`; it is not a field on the podcast form.
 */
class PodcastCoverImageController extends Controller
{
    public function update(UpdatePodcastCoverImageRequest $request, Podcast $podcast): RedirectResponse
    {
        $disk = config('podcasts.cover_disk');
        $previous = $podcast->cover_path;

        $podcast->update([
            'cover_path' => $request->file('cover')->store('podcast-covers', $disk),
        ]);

        if ($previous) {
            Storage::disk($disk)->delete($previous);
        }

        return back()->with('success', __('Cover image updated.'));
    }
}
