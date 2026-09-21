<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('profile page is displayed', function () {
    $this->actingAs(User::factory()->create(['name' => 'Ada']));

    $this->get('/settings/profile')->assertInertia(fn (Assert $page) => $page
        ->component('settings/profile')
        ->where('auth.user.name', 'Ada'));
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->patch('/settings/profile', ['name' => 'Test User', 'email' => 'test@example.com'])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect($user->refresh())
        ->name->toEqual('Test User')
        ->email->toEqual('test@example.com');
});

test('profile name and email are required', function () {
    $user = User::factory()->create(['name' => 'Keep Me']);

    $this->actingAs($user);

    $this->patch('/settings/profile', ['name' => '', 'email' => ''])->assertSessionHasErrors(['name', 'email']);

    expect($user->refresh()->name)->toEqual('Keep Me');
});

test('email must be unique', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->patch('/settings/profile', ['name' => $user->name, 'email' => 'taken@example.com'])
        ->assertSessionHasErrors('email');
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->delete('/settings/profile', ['password' => 'password'])->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->from('/settings/profile')
        ->delete('/settings/profile', ['password' => 'wrong-password'])
        ->assertSessionHasErrors('password')
        ->assertRedirect('/settings/profile');

    expect($user->fresh())->not->toBeNull();
});
