<?php

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('index', function () {
    test('hides draft episodes from non-owners', function () {
        $podcast = Podcast::factory()->create();
        Episode::factory()->for($podcast)->create(['title' => 'Published One']);
        Episode::factory()->for($podcast)->draft()->create(['title' => 'Draft One']);
        $this->actingAs(User::factory()->create());

        $this->get(route('podcast-episodes.index', $podcast))->assertInertia(fn (Assert $page) => $page
            ->component('podcast-episodes/index')
            ->has('episodes.data', 1, fn (Assert $episode) => $episode->where('title', 'Published One')->etc()));
    });

    test('shows drafts to the owner', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create();
        Episode::factory()->for($podcast)->draft()->create(['title' => 'Draft One']);
        $this->actingAs($owner);

        $this->get(route('podcast-episodes.index', $podcast))->assertInertia(fn (Assert $page) => $page
            ->where('podcast.is_owner', true)
            ->has('episodes.data', 1, fn (Assert $episode) => $episode
                ->where('title', 'Draft One')
                ->where('is_published', false)
                ->etc()));
    });
});

describe('store', function () {
    test('forbids adding episodes to a podcast you do not own', function () {
        $podcast = Podcast::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('podcast-episodes.create', $podcast))->assertForbidden();
        $this->post(route('podcast-episodes.store', $podcast), [
            'title' => 'Sneaky',
            'audio_url' => 'https://example.com/a.mp3',
        ])->assertForbidden();

        $this->assertDatabaseCount('episodes', 0);
    });

    test('creates a draft episode scoped to the podcast', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create(['slug' => 'syntax']);
        $this->actingAs($owner);

        $this->post(route('podcast-episodes.store', $podcast), [
            'title' => 'React Hooks',
            'audio_url' => 'https://example.com/hooks.mp3',
            'duration_minutes' => 64,
        ])->assertRedirect('/podcasts/syntax/episodes/react-hooks');

        $this->assertDatabaseHas('episodes', [
            'podcast_id' => $podcast->id,
            'slug' => 'react-hooks',
            'duration_seconds' => 64 * 60,
            'published_at' => null,
        ]);
    });

    test('requires a title and an audio url', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create();
        $this->actingAs($owner);

        $this->post(route('podcast-episodes.store', $podcast), [])
            ->assertSessionHasErrors(['title', 'audio_url']);

        $this->assertDatabaseCount('episodes', 0);
    });
});
