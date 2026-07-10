<?php

use App\Models\User;
use App\Notifications\CustomResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, CustomResetPassword::class);
});

test('reset password screen can be rendered', function () {
    $user = User::factory()->create();
    $token = app('auth.password.broker')->createToken($user);

    $response = $this->get('/reset-password/'.$token);

    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();
    $token = app('auth.password.broker')->createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));
});
