<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TagControllerTest extends TestCase
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

    public function test_find_all_tags()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/tags');

        return $response->assertStatus(200);
    }

    public function test_find_posts_by_tag()
    {
        $token = $this->authenticate();

        $tagId = 1;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/tags/' . $tagId . '/posts');

        return $response->assertStatus(200);
    }

    public function test_find_posts_by_tag_name()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/tags/posts');

        return $response->assertStatus(200);
    }
}
