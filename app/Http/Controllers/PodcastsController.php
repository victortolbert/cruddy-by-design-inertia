<?php

namespace App\Http\Controllers;

use App\Http\Requests\PodcastRequest;
use App\Http\Resources\EpisodeResource;
use App\Http\Resources\PodcastResource;
use App\Models\Podcast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PodcastsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('podcasts/index', [
            'subscribedPodcasts' => PodcastResource::collection($user->subscribedPodcasts()->orderBy('title')->get())->resolve($request),
            'podcasts' => PodcastResource::collection(Podcast::query()->orderBy('title')->paginate(24)),
        ]);
    }

    public function show(Request $request, Podcast $podcast): Response
    {
        Gate::authorize('view', $podcast);

        $user = $request->user();

        return Inertia::render('podcasts/show', [
            'podcast' => PodcastResource::make($podcast)->resolve($request),
            'episodes' => EpisodeResource::collection($podcast->recentEpisodesVisibleTo($user))->resolve($request),
            'subscription' => $user->subscriptionTo($podcast)?->only('id'),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Podcast::class);

        return Inertia::render('podcasts/create');
    }

    public function store(PodcastRequest $request): RedirectResponse
    {
        $podcast = $request->user()->podcasts()->create([
            ...$request->validated(),
            'slug' => Podcast::uniqueSlugFor($request->validated('title')),
        ]);

        return redirect()->route('podcasts.show', $podcast)->with('success', __('Podcast created.'));
    }

    public function edit(Request $request, Podcast $podcast): Response
    {
        Gate::authorize('update', $podcast);

        return Inertia::render('podcasts/edit', [
            'podcast' => PodcastResource::make($podcast)->resolve($request),
        ]);
    }

    public function update(PodcastRequest $request, Podcast $podcast): RedirectResponse
    {
        $podcast->update($request->validated());

        return redirect()->route('podcasts.show', $podcast)->with('success', __('Podcast updated.'));
    }

    public function destroy(Podcast $podcast): RedirectResponse
    {
        Gate::authorize('delete', $podcast);

        if ($podcast->cover_path) {
            Storage::disk(config('podcasts.cover_disk'))->delete($podcast->cover_path);
        }

        $podcast->delete();

        return redirect()->route('podcasts.index')->with('success', __('Podcast deleted.'));
    }
}
