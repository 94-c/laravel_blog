<?php

namespace Tests\Unit;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginUnitTest extends TestCase
{

    public function test_register_user_success()
    {
        $registerUser = [
            'email' => 'test6@test.com',
            'name' => 'test',
            'password' => '1234',
            'password_confirm' => '1234'
        ];

        $response = $this->postJson('/api/register', $registerUser);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_login_success()
    {
        $login = [
            'email' => 'admin@gmail.com',
            'password' => '1234',
        ];

        $response = $this->postJson('/api/login', $login);

        $response->assertStatus(Response::HTTP_ACCEPTED);

    }


}
