<?php

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('show', function () {
    test('renders a published episode under its podcast', function () {
        $this->actingAs(User::factory()->create());
        $podcast = Podcast::factory()->create(['slug' => 'syntax']);
        Episode::factory()->for($podcast)->create(['slug' => 'css-grid', 'title' => 'CSS Grid']);

        $this->get('/podcasts/syntax/episodes/css-grid')->assertInertia(fn (Assert $page) => $page
            ->component('episodes/show')
            ->where('episode.title', 'CSS Grid')
            ->where('podcast.slug', 'syntax')
            ->where('progress', null));
    });

    test('returns 404 when the episode belongs to a different podcast', function () {
        $this->actingAs(User::factory()->create());
        Podcast::factory()->create(['slug' => 'syntax']);
        $other = Podcast::factory()->create(['slug' => 'shop-talk']);
        Episode::factory()->for($other)->create(['slug' => 'css-grid']);

        $this->get('/podcasts/syntax/episodes/css-grid')->assertNotFound();
    });

    test('forbids non-owners from viewing a draft', function () {
        $podcast = Podcast::factory()->create();
        $episode = Episode::factory()->for($podcast)->draft()->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('episodes.show', [$podcast, $episode]))->assertForbidden();
    });

    test('lets the owner view a draft', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create();
        $episode = Episode::factory()->for($podcast)->draft()->create();
        $this->actingAs($owner);

        $this->get(route('episodes.show', [$podcast, $episode]))
            ->assertInertia(fn (Assert $page) => $page->where('episode.is_published', false));
    });
});

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
        ])->assertRedirect(route('episodes.show', [$podcast, $episode]));

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
