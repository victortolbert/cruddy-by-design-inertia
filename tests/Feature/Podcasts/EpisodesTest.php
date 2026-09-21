<?php

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('update', function () {
    test('forbids editing an episode of a podcast you do not own', function () {
        $podcast = Podcast::factory()->create();
        $episode = Episode::factory()->for($podcast)->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('episodes.edit', [$podcast, $episode]))->assertForbidden();
        $this->put(route('episodes.update', [$podcast, $episode]), [
            'title' => 'Hijacked',
            'audio_url' => $episode->audio_url,
        ])->assertForbidden();
    });

    test('updates the episode without changing its publish state', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create();
        $episode = Episode::factory()->for($podcast)->create(['duration_seconds' => 3600]);
        $this->actingAs($owner);

        $this->get(route('episodes.edit', [$podcast, $episode]))
            ->assertInertia(fn (Assert $page) => $page->where('episode.duration_seconds', 3600));

        $this->put(route('episodes.update', [$podcast, $episode]), [
            'title' => 'Renamed',
            'audio_url' => $episode->audio_url,
            'duration_minutes' => 45,
        ])->assertRedirect(route('podcast-episodes.index', $podcast));

        expect($episode->fresh())
            ->title->toBe('Renamed')
            ->duration_seconds->toBe(2700)
            ->isPublished()->toBeTrue();
    });
});

describe('destroy', function () {
    test('deletes the episode', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create();
        $episode = Episode::factory()->for($podcast)->create();
        $this->actingAs($owner);

        $this->delete(route('episodes.destroy', [$podcast, $episode]))
            ->assertRedirect(route('podcast-episodes.index', $podcast));

        $this->assertModelMissing($episode);
    });
});
