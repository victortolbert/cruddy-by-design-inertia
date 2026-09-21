<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublishedEpisodeRequest;
use App\Models\Episode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/**
 * Tip 4: a state is a resource. Publishing an episode is `store` on
 * PublishedEpisodes; unpublishing is `destroy` — not `publish()` on Episodes.
 */
class PublishedEpisodesController extends Controller
{
    public function store(StorePublishedEpisodeRequest $request): RedirectResponse
    {
        $episode = Episode::query()->findOrFail($request->integer('episode_id'));

        Gate::authorize('update', $episode);

        $episode->publish();

        return back()->with('success', __('Episode published.'));
    }

    public function destroy(Episode $episode): RedirectResponse
    {
        Gate::authorize('update', $episode);

        $episode->unpublish();

        return back()->with('success', __('Episode moved back to drafts.'));
    }
}
