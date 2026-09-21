<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePlaybackProgressRequest;
use App\Models\Episode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Playback position is independent of the episode, so it is its own resource:
 * the player calls `update` as it plays; `destroy` resets the listener's place.
 */
class PlaybackProgressController extends Controller
{
    /**
     * Completion is sticky: resuming a finished episode does not un-complete
     * it. Only `destroy` clears that.
     */
    public function update(UpdatePlaybackProgressRequest $request, Episode $episode): RedirectResponse
    {
        $user = $request->user();
        $existing = $user->playbackProgressFor($episode);

        $user->playbackProgress()->updateOrCreate(
            ['episode_id' => $episode->id],
            [
                'position_seconds' => $request->integer('position'),
                'completed_at' => $request->boolean('completed') ? now() : $existing?->completed_at,
            ],
        );

        return back();
    }

    public function destroy(Request $request, Episode $episode): RedirectResponse
    {
        $request->user()->playbackProgressFor($episode)?->delete();

        return back()->with('success', __('Progress reset.'));
    }
}
