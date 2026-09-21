<?php

namespace App\Http\Controllers;

use App\Http\Requests\EpisodeRequest;
use App\Http\Resources\EpisodeResource;
use App\Http\Resources\PodcastResource;
use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Tip 1: the nested resource gets its own controller. This is
 * PodcastEpisodes@index, not Podcasts@episodes.
 */
class PodcastEpisodesController extends Controller
{
    public function index(Request $request, Podcast $podcast): Response
    {
        Gate::authorize('view', $podcast);

        $isOwner = $podcast->isOwnedBy($request->user());

        $episodes = $podcast->episodes()
            ->when(! $isOwner, fn (Builder $query): Builder => $query->published())
            ->recent()
            ->paginate(20);

        return Inertia::render('podcast-episodes/index', [
            'podcast' => PodcastResource::make($podcast)->resolve($request),
            'episodes' => EpisodeResource::collection($episodes),
        ]);
    }

    public function create(Request $request, Podcast $podcast): Response
    {
        Gate::authorize('update', $podcast);

        return Inertia::render('podcast-episodes/create', [
            'podcast' => PodcastResource::make($podcast)->resolve($request),
        ]);
    }

    /**
     * New episodes always start as drafts; publishing is its own resource.
     */
    public function store(EpisodeRequest $request, Podcast $podcast): RedirectResponse
    {
        $episode = $podcast->episodes()->create([
            ...$request->episodeAttributes(),
            'slug' => Episode::uniqueSlugFor($podcast, $request->validated('title')),
        ]);

        return redirect()->route('episodes.show', [$podcast, $episode])->with('success', __('Episode saved as a draft.'));
    }
}
