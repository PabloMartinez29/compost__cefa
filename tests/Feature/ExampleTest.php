<?php

it('returns a successful response for the home page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('returns a successful response for the login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

it('returns a successful response for the register page', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

it('returns a successful response for the developers page', function () {
    $response = $this->get('/developers');

    $response->assertStatus(200);
});

it('redirects guests from the dashboard to login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login', absolute: false));
});
