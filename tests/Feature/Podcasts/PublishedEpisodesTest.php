<?php

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;

describe('store', function () {
    test('publishes a draft episode', function () {
        $owner = User::factory()->create();
        $episode = Episode::factory()->for(Podcast::factory()->for($owner, 'owner'))->draft()->create();
        $this->actingAs($owner);

        $this->post(route('published-episodes.store'), ['episode_id' => $episode->id])->assertRedirect();

        expect($episode->fresh()->isPublished())->toBeTrue();
    });

    test('forbids non-owners from publishing', function () {
        $episode = Episode::factory()->draft()->create();
        $this->actingAs(User::factory()->create());

        $this->post(route('published-episodes.store'), ['episode_id' => $episode->id])->assertForbidden();

        expect($episode->fresh()->isPublished())->toBeFalse();
    });
});

describe('destroy', function () {
    test('moves a published episode back to drafts', function () {
        $owner = User::factory()->create();
        $episode = Episode::factory()->for(Podcast::factory()->for($owner, 'owner'))->create();
        $this->actingAs($owner);

        $this->delete(route('published-episodes.destroy', $episode))->assertRedirect();

        expect($episode->fresh()->isPublished())->toBeFalse();
    });

    test('forbids non-owners from unpublishing', function () {
        $episode = Episode::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->delete(route('published-episodes.destroy', $episode))->assertForbidden();

        expect($episode->fresh()->isPublished())->toBeTrue();
    });
});
