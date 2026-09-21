<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('appearance page is displayed', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/settings/appearance')
        ->assertInertia(fn (Assert $page) => $page->component('settings/appearance'));
});
