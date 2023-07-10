<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    protected function authenticate()
    {
        $login = [
            'email' => 'admin@gmail.com',
            'password' => '1234',
            'provider' => 'mailTrap'
        ];

        $token = Auth::guard('api')->attempt($login);

        return $token;
    }

    public function test_register_success()
    {
        $register = [
            'email' => 'test2@test.com',
            'name' => 'test',
            'password' => md5('1234'),
            'password_confirm' => md5('1234'),
        ];

        $this->json('POST', 'api/register', $register)
            ->assertStatus(201);

    }

    public function test_register_fail()
    {
        $register = [
            'email' => 'test@test.com',
            'name' => 'test',
            'password' => md5('1234'),
            'password_confirm' => md5('22222'),
        ];

        $this->json('POST', 'api/register', $register)
            ->assertStatus(201);

    }

    public function test_login_success()
    {
        $login = [
            'email' => 'admin@gmail.com',
            'password' => '1234'
        ];

        $response = $this->json('POST', 'api/login', $login);

        $response->assertStatus(202);

        $this->assertAuthenticated('api');

    }

    public function test_login_fail()
    {
        $register = [
            'email' => 'admin@gmail.com',
            'password' => '12334'
        ];

        $this->json('POST', 'api/login', $register)
            ->assertStatus(202);

    }

    public function test_logout_success()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/logout');

        $response->assertStatus(200);
    }

}
