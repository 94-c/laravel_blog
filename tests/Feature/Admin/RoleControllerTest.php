<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RoleControllerTest extends TestCase
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

    public function test_roles()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/roles')->assertStatus(200);

    }

    public function test_roles_users()
    {
        $token = $this->authenticate();

        $roles = 4;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/roles/' . $roles . '/users')->assertStatus(200);

    }
}
