<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaybackProgressResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Tip 4: a playback state as a filterable resource.
 */
class InProgressEpisodesController extends Controller
{
    public function index(Request $request): Response
    {
        $inProgress = $request->user()->playbackProgress()
            ->inProgress()
            ->whereHas('episode', fn ($query) => $query->published())
            ->with('episode.podcast')
            ->latest('updated_at')
            ->get();

        return Inertia::render('in-progress-episodes/index', [
            'inProgress' => PlaybackProgressResource::collection($inProgress)->resolve($request),
        ]);
    }
}
