<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('account page shares the email', function () {
    $this->actingAs(User::factory()->create(['email' => 'member@example.com']));

    $this->get('/settings/account')->assertInertia(fn (Assert $page) => $page
        ->component('settings/account/edit')
        ->where('auth.user.email', 'member@example.com'));
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->delete('/settings/account', ['password' => 'password'])->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->from('/settings/account')
        ->delete('/settings/account', ['password' => 'wrong-password'])
        ->assertSessionHasErrors('password')
        ->assertRedirect('/settings/account');

    expect($user->fresh())->not->toBeNull();
});
