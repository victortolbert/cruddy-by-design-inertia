<?php

use App\Models\Episode;
use App\Models\PlaybackProgress;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('update', function () {
    test('records the position for the current user', function () {
        $user = User::factory()->create();
        $episode = Episode::factory()->create();
        $this->actingAs($user);

        $this->put(route('playback-progress.update', $episode), ['position' => 90])->assertRedirect();

        $this->assertDatabaseHas('playback_progress', [
            'user_id' => $user->id,
            'episode_id' => $episode->id,
            'position_seconds' => 90,
            'completed_at' => null,
        ]);
    });

    test('marks the episode completed and keeps it completed on later updates', function () {
        $user = User::factory()->create();
        $episode = Episode::factory()->create();
        $this->actingAs($user);

        $this->put(route('playback-progress.update', $episode), ['position' => 3000, 'completed' => true]);
        $this->put(route('playback-progress.update', $episode), ['position' => 10]);

        $progress = $user->playbackProgressFor($episode);
        expect($progress->isCompleted())->toBeTrue()
            ->and($progress->position_seconds)->toBe(10);
    });

    test('rejects a negative position', function () {
        $this->actingAs(User::factory()->create());
        $episode = Episode::factory()->create();

        $this->put(route('playback-progress.update', $episode), ['position' => -1])
            ->assertSessionHasErrors('position');

        $this->assertDatabaseCount('playback_progress', 0);
    });

    test('forbids progress on a draft you cannot see', function () {
        $episode = Episode::factory()->draft()->create();
        $this->actingAs(User::factory()->create());

        $this->put(route('playback-progress.update', $episode), ['position' => 30])->assertForbidden();

        $this->assertDatabaseCount('playback_progress', 0);
    });
});

describe('destroy', function () {
    test('resets progress for the current user only', function () {
        $user = User::factory()->create();
        $episode = Episode::factory()->create();
        $mine = PlaybackProgress::factory()->for($user)->for($episode)->create();
        $theirs = PlaybackProgress::factory()->for($episode)->create();
        $this->actingAs($user);

        $this->delete(route('playback-progress.destroy', $episode))->assertRedirect();

        $this->assertModelMissing($mine);
        $this->assertModelExists($theirs);
    });
});

describe('in-progress episodes', function () {
    test('lists in-progress episodes but not completed ones', function () {
        $user = User::factory()->create();
        $started = Episode::factory()->create(['title' => 'Started Episode']);
        $finished = Episode::factory()->create(['title' => 'Finished Episode']);
        PlaybackProgress::factory()->for($user)->for($started)->create();
        PlaybackProgress::factory()->for($user)->for($finished)->completed()->create();
        $this->actingAs($user);

        $this->get('/in-progress-episodes')->assertInertia(fn (Assert $page) => $page
            ->component('in-progress-episodes/index')
            ->has('inProgress', 1, fn (Assert $progress) => $progress->where('episode.title', 'Started Episode')->etc()));
    });

    test('shows continue listening on the podcasts index', function () {
        $user = User::factory()->create();
        $episode = Episode::factory()->create(['title' => 'Started Episode']);
        PlaybackProgress::factory()->for($user)->for($episode)->create();
        $this->actingAs($user);

        $this->get('/podcasts')->assertInertia(fn (Assert $page) => $page
            ->has('inProgress', 1, fn (Assert $progress) => $progress->where('episode.title', 'Started Episode')->etc()));
    });
});
