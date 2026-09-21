<?php

use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\PodcastCoverImageController;
use App\Http\Controllers\PodcastEpisodesController;
use App\Http\Controllers\PodcastsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Podcast Routes — CRUDdy by Design
|--------------------------------------------------------------------------
|
| Every controller exposes only the seven resource verbs. Anything that would
| have been a custom action (subscribe, publish, upload cover, track progress)
| is a resource of its own. See /podcasts/patterns.
|
*/

Route::middleware('auth')->group(function () {
    Route::inertia('podcasts/patterns', 'podcasts/patterns')->name('podcasts.patterns');

    Route::resource('podcasts', PodcastsController::class)
        ->parameters(['podcasts' => 'podcast:slug']);

    // Tip 1: nested resource gets its own controller
    Route::resource('podcasts.episodes', PodcastEpisodesController::class)
        ->only(['index', 'create', 'store'])
        ->parameters(['podcasts' => 'podcast:slug'])
        ->names('podcast-episodes');

    // Episodes are addressed under their podcast because slugs are unique per podcast
    Route::resource('podcasts.episodes', EpisodesController::class)
        ->only(['show', 'edit', 'update', 'destroy'])
        ->parameters(['podcasts' => 'podcast', 'episodes' => 'episode'])
        ->scoped(['podcast' => 'slug', 'episode' => 'slug'])
        ->names('episodes');

    // Tip 2: a property edited on its own is its own resource
    Route::put('podcasts/{podcast:slug}/cover-image', [PodcastCoverImageController::class, 'update'])->name('podcast-cover-image.update');
});
