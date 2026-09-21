<?php

use App\Models\Podcast;
use App\Models\Subscription;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('store', function () {
    test('creates a subscription for the current user', function () {
        $user = User::factory()->create();
        $podcast = Podcast::factory()->create();
        $this->actingAs($user);

        $this->from(route('podcasts.show', $podcast))
            ->post(route('subscriptions.store'), ['podcast_id' => $podcast->id])
            ->assertRedirect(route('podcasts.show', $podcast));

        $this->assertDatabaseHas('subscriptions', ['user_id' => $user->id, 'podcast_id' => $podcast->id]);
    });

    test('does not duplicate an existing subscription', function () {
        $user = User::factory()->create();
        $podcast = Podcast::factory()->create();
        Subscription::factory()->for($user)->for($podcast)->create();
        $this->actingAs($user);

        $this->post(route('subscriptions.store'), ['podcast_id' => $podcast->id]);

        $this->assertDatabaseCount('subscriptions', 1);
    });

    test('rejects an unknown podcast', function () {
        $this->actingAs(User::factory()->create());

        $this->post(route('subscriptions.store'), ['podcast_id' => 999])
            ->assertSessionHasErrors('podcast_id');

        $this->assertDatabaseCount('subscriptions', 0);
    });
});

describe('destroy', function () {
    test('removes the current user\'s subscription', function () {
        $user = User::factory()->create();
        $mine = Subscription::factory()->for($user)->create();
        $this->actingAs($user);

        $this->delete(route('subscriptions.destroy', $mine))->assertRedirect();

        $this->assertModelMissing($mine);
    });

    test('returns 404 for another user\'s subscription', function () {
        $theirs = Subscription::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->delete(route('subscriptions.destroy', $theirs))->assertNotFound();

        $this->assertModelExists($theirs);
    });
});

describe('index', function () {
    test('lists only the current user\'s subscriptions', function () {
        $user = User::factory()->create();
        $mine = Podcast::factory()->create(['title' => 'Mine Show']);
        Subscription::factory()->for($user)->for($mine)->create();
        Subscription::factory()->create();
        $this->actingAs($user);

        $this->get('/subscriptions')->assertInertia(fn (Assert $page) => $page
            ->component('subscriptions/index')
            ->has('subscriptions', 1, fn (Assert $subscription) => $subscription
                ->where('podcast.title', 'Mine Show')
                ->etc()));
    });
});
