<?php

test('email verification is not enabled in this project', function () {
    // User model does not implement MustVerifyEmail.
})->skip('Email verification is not enabled on the User model.');
