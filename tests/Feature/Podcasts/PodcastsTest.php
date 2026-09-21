<?php

use App\Models\Podcast;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('index', function () {
    test('guests are redirected to the login page', function () {
        $this->get('/podcasts')->assertRedirect('/login');
    });

    test('lists every podcast', function () {
        Podcast::factory()->create(['title' => 'First Show']);
        Podcast::factory()->create(['title' => 'Other Show']);

        $this->actingAs(User::factory()->create());

        $this->get('/podcasts')->assertInertia(fn (Assert $page) => $page
            ->component('podcasts/index')
            ->has('podcasts.data', 2)
            ->where('podcasts.meta.total', 2));
    });
});

describe('show', function () {
    test('renders a podcast by slug', function () {
        $this->actingAs(User::factory()->create());
        Podcast::factory()->create(['slug' => 'full-stack-radio', 'title' => 'Full Stack Radio']);

        $this->get('/podcasts/full-stack-radio')->assertInertia(fn (Assert $page) => $page
            ->component('podcasts/show')
            ->where('podcast.title', 'Full Stack Radio')
            ->where('podcast.is_owner', false));
    });

    test('returns 404 for an unknown slug', function () {
        $this->actingAs(User::factory()->create());

        $this->get('/podcasts/nope')->assertNotFound();
    });

    test('flags ownership for the owner only', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create();

        $this->actingAs($owner)->get(route('podcasts.show', $podcast))
            ->assertInertia(fn (Assert $page) => $page->where('podcast.is_owner', true));
        $this->actingAs(User::factory()->create())->get(route('podcasts.show', $podcast))
            ->assertInertia(fn (Assert $page) => $page->where('podcast.is_owner', false));
    });
});

describe('store', function () {
    test('creates a podcast owned by the current user with a slug from the title', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('podcasts.store'), [
            'title' => 'Full Stack Radio',
            'website' => 'https://fullstackradio.com',
        ])->assertRedirect('/podcasts/full-stack-radio');

        $this->assertDatabaseHas('podcasts', [
            'user_id' => $user->id,
            'title' => 'Full Stack Radio',
            'slug' => 'full-stack-radio',
            'website' => 'https://fullstackradio.com',
        ]);
    });

    test('suffixes the slug when the title is already taken', function () {
        $this->actingAs(User::factory()->create());
        Podcast::factory()->create(['slug' => 'syntax']);

        $this->post(route('podcasts.store'), ['title' => 'Syntax'])
            ->assertRedirect('/podcasts/syntax-2');
    });

    test('requires a title and a valid website', function () {
        $this->actingAs(User::factory()->create());

        $this->from(route('podcasts.create'))
            ->post(route('podcasts.store'), ['title' => '', 'website' => 'not a url'])
            ->assertRedirect(route('podcasts.create'))
            ->assertSessionHasErrors(['title', 'website']);

        $this->assertDatabaseCount('podcasts', 0);
    });
});

describe('update', function () {
    test('forbids editing a podcast you do not own', function () {
        $podcast = Podcast::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('podcasts.edit', $podcast))->assertForbidden();
        $this->put(route('podcasts.update', $podcast), ['title' => 'Hijacked'])->assertForbidden();
    });

    test('updates the podcast and keeps its slug', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create(['slug' => 'syntax', 'title' => 'Syntax']);
        $this->actingAs($owner);

        $this->put(route('podcasts.update', $podcast), [
            'title' => 'Syntax FM',
            'author' => 'Wes & Scott',
        ])->assertRedirect('/podcasts/syntax');

        expect($podcast->fresh())
            ->title->toBe('Syntax FM')
            ->author->toBe('Wes & Scott')
            ->slug->toBe('syntax');
    });
});

describe('destroy', function () {
    test('deletes the podcast and its episodes', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->hasEpisodes(2)->create();
        $this->actingAs($owner);

        $this->delete(route('podcasts.destroy', $podcast))->assertRedirect('/podcasts');

        $this->assertModelMissing($podcast);
        $this->assertDatabaseCount('episodes', 0);
    });

    test('forbids deleting a podcast you do not own', function () {
        $podcast = Podcast::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->delete(route('podcasts.destroy', $podcast))->assertForbidden();

        $this->assertModelExists($podcast);
    });
});
