<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

test('register works', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'john9@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'reader',
    ]);

    if ($response->status() === 302 && session()->has('errors')) {
        var_dump(session('errors')->getMessages());
    }

    $response->assertRedirect('/');
    $this->assertAuthenticated();
});
