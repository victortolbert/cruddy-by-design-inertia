<?php

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;
use App\Policies\EpisodePolicy;

function episodeOwnedBy(int $userId, bool $published): Episode
{
    $podcast = Podcast::factory()->make(['user_id' => $userId]);
    $episode = Episode::factory()->make($published ? [] : ['published_at' => null]);
    $episode->setRelation('podcast', $podcast);

    return $episode;
}

test('published episodes are visible to everyone', function () {
    $stranger = User::factory()->make(['id' => 2]);

    expect((new EpisodePolicy)->view($stranger, episodeOwnedBy(1, published: true)))->toBeTrue();
});

test('drafts are visible only to the podcast owner', function () {
    $owner = User::factory()->make(['id' => 1]);
    $stranger = User::factory()->make(['id' => 2]);
    $draft = episodeOwnedBy(1, published: false);

    expect((new EpisodePolicy)->view($owner, $draft))->toBeTrue()
        ->and((new EpisodePolicy)->view($stranger, $draft))->toBeFalse();
});

test('only the podcast owner may update or delete an episode', function () {
    $owner = User::factory()->make(['id' => 1]);
    $stranger = User::factory()->make(['id' => 2]);
    $episode = episodeOwnedBy(1, published: true);

    expect((new EpisodePolicy)->update($owner, $episode))->toBeTrue()
        ->and((new EpisodePolicy)->delete($owner, $episode))->toBeTrue()
        ->and((new EpisodePolicy)->update($stranger, $episode))->toBeFalse()
        ->and((new EpisodePolicy)->delete($stranger, $episode))->toBeFalse();
});
