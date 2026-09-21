<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaybackProgressResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Tip 4: a playback state as a filterable resource.
 */
class CompletedEpisodesController extends Controller
{
    public function index(Request $request): Response
    {
        $completed = $request->user()->playbackProgress()
            ->completed()
            ->whereHas('episode', fn ($query) => $query->published())
            ->with('episode.podcast')
            ->latest('completed_at')
            ->get();

        return Inertia::render('completed-episodes/index', [
            'completed' => PlaybackProgressResource::collection($completed)->resolve($request),
        ]);
    }
}
