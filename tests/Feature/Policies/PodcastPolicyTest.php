<?php

use App\Models\Podcast;
use App\Models\User;
use App\Policies\PodcastPolicy;

test('any member may view and create podcasts', function () {
    $user = User::factory()->make(['id' => 1]);
    $podcast = Podcast::factory()->make(['user_id' => 2]);

    expect((new PodcastPolicy)->view($user, $podcast))->toBeTrue()
        ->and((new PodcastPolicy)->create($user))->toBeTrue();
});

test('only the owner may update or delete a podcast', function () {
    $owner = User::factory()->make(['id' => 1]);
    $stranger = User::factory()->make(['id' => 2]);
    $podcast = Podcast::factory()->make(['user_id' => 1]);

    expect((new PodcastPolicy)->update($owner, $podcast))->toBeTrue()
        ->and((new PodcastPolicy)->delete($owner, $podcast))->toBeTrue()
        ->and((new PodcastPolicy)->update($stranger, $podcast))->toBeFalse()
        ->and((new PodcastPolicy)->delete($stranger, $podcast))->toBeFalse();
});
