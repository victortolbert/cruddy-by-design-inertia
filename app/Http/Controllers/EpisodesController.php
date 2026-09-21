<?php

namespace App\Http\Controllers;

use App\Http\Requests\EpisodeRequest;
use App\Http\Resources\EpisodeResource;
use App\Http\Resources\PodcastResource;
use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class EpisodesController extends Controller
{
    public function edit(Request $request, Podcast $podcast, Episode $episode): Response
    {
        Gate::authorize('update', $episode);

        return Inertia::render('episodes/edit', [
            'podcast' => PodcastResource::make($podcast)->resolve($request),
            'episode' => EpisodeResource::make($episode)->resolve($request),
        ]);
    }

    public function update(EpisodeRequest $request, Podcast $podcast, Episode $episode): RedirectResponse
    {
        $episode->update($request->episodeAttributes());

        return redirect()->route('podcast-episodes.index', $podcast)->with('success', __('Episode updated.'));
    }

    public function destroy(Podcast $podcast, Episode $episode): RedirectResponse
    {
        Gate::authorize('delete', $episode);

        $episode->delete();

        return redirect()->route('podcast-episodes.index', $podcast)->with('success', __('Episode deleted.'));
    }
}
