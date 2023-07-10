<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PostControllerTest extends TestCase
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

    public function test_find_all_posts()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/posts')->assertStatus(200);

    }

    public function test_find_by_posts()
    {
        $token = $this->authenticate();

        $postId = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/posts/' . $postId)->assertStatus(200);

    }

    public function test_enable_posts()
    {
        $token = $this->authenticate();

        $postId = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/admin/posts/' . $postId . '/enable')->assertStatus(200);

    }

    public function test_disable_posts()
    {
        $token = $this->authenticate();

        $postId = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/admin/posts/' . $postId . '/disable')->assertStatus(200);

    }

}
