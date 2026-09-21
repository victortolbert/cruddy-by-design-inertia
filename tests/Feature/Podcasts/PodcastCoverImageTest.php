<?php

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake(config('podcasts.cover_disk'));
});

describe('update', function () {
    test('stores the cover and replaces the previous file', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create(['cover_path' => 'podcast-covers/old.jpg']);
        Storage::disk(config('podcasts.cover_disk'))->put('podcast-covers/old.jpg', 'old');
        $this->actingAs($owner);

        $this->put(route('podcast-cover-image.update', $podcast), [
            'cover' => UploadedFile::fake()->image('cover.jpg', 600, 600),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $podcast->refresh();
        expect($podcast->cover_path)->toStartWith('podcast-covers/')->not->toBe('podcast-covers/old.jpg');
        Storage::disk(config('podcasts.cover_disk'))->assertExists($podcast->cover_path);
        Storage::disk(config('podcasts.cover_disk'))->assertMissing('podcast-covers/old.jpg');
    });

    test('rejects images smaller than 500px', function () {
        $owner = User::factory()->create();
        $podcast = Podcast::factory()->for($owner, 'owner')->create();
        $this->actingAs($owner);

        $this->put(route('podcast-cover-image.update', $podcast), [
            'cover' => UploadedFile::fake()->image('cover.jpg', 200, 200),
        ])->assertSessionHasErrors('cover');

        expect($podcast->fresh()->cover_path)->toBeNull();
    });

    test('forbids non-owners', function () {
        $podcast = Podcast::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->put(route('podcast-cover-image.update', $podcast), [
            'cover' => UploadedFile::fake()->image('cover.jpg', 600, 600),
        ])->assertForbidden();
    });
});
