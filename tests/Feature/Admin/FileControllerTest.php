<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FileControllerTest extends TestCase
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

    public function test_find_all_files()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/files')->assertStatus(200);

    }

    public function test_find_all_images()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/files')->assertStatus(200);

    }


}
